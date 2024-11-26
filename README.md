# **Operational Framework for Multiple EV Charging Stations**

---

## **Abstract**

Electric Vehicles (EVs) are rapidly replacing traditional petrol or diesel vehicles due to their economical operation and low environmental pollution. However, challenges like longer battery charging times compared to refueling times create waiting queues during peak hours at charging stations. Additionally, the rising adoption of EVs impacts the stability of power grids, necessitating the installation of efficient charging stations.

This paper proposes an operational framework for multiple EV charging stations to:  
1. Reduce queue size and waiting time.  
2. Maintain power grid stability.  

Our analysis is based on a multi-queue system used to model charging station dynamics. We propose:  
- An optimization problem to minimize customer waiting time.  
- A size-constrained optimal EV allocation algorithm.  
- A price control method to influence customer behavior and achieve optimal station allocation.

---

## **Introduction**

The EV market in India is projected to reach **63 lakh units annually by 2027** (CAGR: 44%) and over **250 million EVs by 2030**, contributing to a projected electricity demand of **640 TWh by 2030**. This growth demands the establishment of efficient EV charging stations that:  
- Ensure power grid stability.  
- Provide reasonable Quality of Service (QoS).  

We propose a framework to optimize the performance of multiple EV charging stations by balancing throughput and customer satisfaction. The framework includes:  
1. Real-time price-based behavior control for customers.  
2. Real-time station status (e.g., fully occupied or not).  
3. Dynamic pricing to demotivate charging during peak hours, thus controlling customer behavior.

---

## **Problem Description**

We consider an urban area with multiple charging stations (\( n \)) operating as a multi-queue system. Each station is modeled as an \( M/M/1 \) queue:  
- \( S_i(t) \): Queue size of station \( i \) at time \( t \).  
- \( \mu_i \): Charging time rate for station \( i \).  
- \( \lambda \): Poisson arrival rate of EVs.

### **Key Assumptions**
- Each station follows a Poisson process for EV arrivals.  
- The global arrival rate \( \lambda = \sum_{i=1}^n \lambda_i \).  
- Mean queue size \( E[N_i] \) relates to waiting time via Little’s Law:  
  \[
  E[N_i] = \frac{\mu_i}{\mu_i - \lambda_i}
  \]

### **Objective**
Optimize EV allocation to:  
1. Minimize global queue size.  
2. Prevent overflow at individual stations.

---

## **Vehicle Allocation Analysis**

### **Optimized Load Balancing**
The optimization minimizes the global queue size by finding the arrival rate vector \( \vec{\lambda} \):  
\[
\text{Minimize } \sum_{i=1}^n E[N_i] = \sum_{i=1}^n \frac{\mu_i}{\mu_i - \lambda_i}
\]
Subject to:  
- Load balancing through Poisson thinning.  
- Stability of queues (\( \lambda_i < \mu_i \)).  
- Positive arrival and departure rates.

### **Station Size Limitation and AQM Algorithm**
To prevent station overflow during peak times:  
- Use size-based Active Queue Management (AQM).  
- Congestion indicator:  
  \[
  c_i(t) = 1 \{ Q_i(t) > L_i \}
  \]
  Where \( L_i \) is the station size limit.  
- Adjust allocation dynamically based on congestion using Algorithm 1.

---

## **Customer Control Using Dynamic Pricing**

Dynamic pricing influences customer decisions by varying charging costs.  
### **Framework**
- Time slots (\( T \)): Prices updated hourly.  
- Arrival rate difference:  
  \[
  d_i(t) = \lambda_i(t) - \lambda_i^{opt}(t)
  \]
  A positive \( d_i(t) \) indicates excess demand at station \( i \).

### **Pricing Formula**
\[
p_i(t) = \text{Base Price} + \gamma d_i(t)
\]
Where \( \gamma \) is a constant.

---

## **Predicting Arrival Rate**

To set the price for a specific time slot (e.g., 10 a.m.–11 a.m.), we use Random Forest Regression on historical arrival data.

### **Random Forest Regression**
- **Definition**: Combines multiple decision trees to improve predictive accuracy.  
- **Advantages**:  
  - Efficient for large datasets.  
  - Handles missing data effectively.  
  - Predicts with high accuracy.

### **Dataset**
The dataset includes hourly time slots and corresponding vehicle arrival counts. Features are extracted for model training to predict future arrival rates.

---

## **References**
1. Daehyun Ban et al., Optimal Load Balancing in Multi-Queue EV Charging.  
2. India Energy Storage Alliance (IESA) Report, 2020-2027.  
3. DataLabs by Inc42 Research, EV Market Insights.  
4. Steffen Limmer, Dynamic Pricing Strategies.  
5. Guo et al., Coarse-Grained Price Profiles for EV Charging.  
6. GeeksforGeeks Dataset for EV Arrival Prediction.

---

### **Note**  
This framework is a step toward efficient EV infrastructure development to meet future demands while ensuring customer satisfaction and power grid stability.

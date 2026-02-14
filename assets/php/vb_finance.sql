-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 14, 2026 at 06:21 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `vb_finance`
--

-- --------------------------------------------------------

--
-- Table structure for table `bank_data`
--

CREATE TABLE `bank_data` (
  `b_id` int(11) NOT NULL,
  `trans_date` date NOT NULL,
  `narration` varchar(255) NOT NULL,
  `chq_ref_no` varchar(255) NOT NULL,
  `value_date` date NOT NULL,
  `withdrawal_amount` decimal(10,2) NOT NULL,
  `deposit_amount` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `bank_data`
--

INSERT INTO `bank_data` (`b_id`, `trans_date`, `narration`, `chq_ref_no`, `value_date`, `withdrawal_amount`, `deposit_amount`) VALUES
(1, '2026-02-01', 'POS 403875XXXXXX7099 BHIKHARAM CHANDM', '0000603208548907', '2026-02-01', 45.00, 0.00),
(2, '2026-02-01', 'ATW-403875XXXXXX7099-MC002953-HOWRAH', '0000603212388215', '2026-02-01', 1000.00, 0.00),
(3, '2026-02-01', 'POS 403875XXXXXX7099 SPENCERS RETAIL', '0000000000006668', '2026-02-01', 94.75, 0.00),
(4, '2026-02-02', 'NEFT CR-UTIB0001506-RAZORPAY PAYMENTS PVT LTD PAYMENT AGGREGATOR ESCR-VIKASH BAGARIA-AXISCN1237495209', 'AXISCN1237495209', '2026-02-02', 0.00, 2300.12),
(5, '2026-02-02', '05311000015529-TPT-NBN6XWPJKPOBNWQZ-VIKASH BAGARIA', '0000000510354991', '2026-02-02', 10000.00, 0.00),
(6, '2026-02-03', 'NEFT CR-UTIB0001506-RAZORPAY PAYMENTS PVT LTD PAYMENT AGGREGATOR ESCR-VIKASH BAGARIA-AXISCN1238757513', 'AXISCN1238757513', '2026-02-03', 0.00, 5200.00),
(7, '2026-02-04', 'NEFT CR-UTIB0001506-RAZORPAY PAYMENTS PVT LTD PAYMENT AGGREGATOR ESCR-VIKASH BAGARIA-AXISCN1240498634', 'AXISCN1240498634', '2026-02-04', 0.00, 2200.00),
(8, '2026-02-04', 'POS 403875XXXXXX7099 JUBILANT FOODWOR', '0000603518281638', '2026-02-04', 153.89, 0.00),
(9, '2026-02-05', 'POS 403875XXXXXX7099 GST', '0000603647044548', '2026-02-05', 22000.00, 0.00),
(10, '2026-02-06', 'NEFT CR-UTIB0001506-RAZORPAY PAYMENTS PVT LTD PAYMENT AGGREGATOR ESCR-VIKASH BAGARIA-AXISCN1242518629', 'AXISCN1242518629', '2026-02-06', 0.00, 1750.31),
(11, '2026-02-06', '05311000015529-TPT-NBBPI0D2KBSY06ST-VIKASH BAGARIA', '0000000143963036', '2026-02-06', 12000.00, 0.00),
(12, '2026-02-06', 'POS 403875XXXXXX7099 SPENCERS RETAIL', '0000000000008024', '2026-02-06', 303.40, 0.00),
(13, '2026-02-07', 'POS 403875XXXXXX7099 CHAI BREAK EXPRE', '0000603811459834', '2026-02-07', 470.00, 0.00),
(14, '2026-02-07', 'ATW-403875XXXXXX7099-MC002953-HOWRAH', '0000603811404905', '2026-02-07', 10000.00, 0.00),
(15, '2026-02-07', 'POS 403875XXXXXX7099 FUNTAIL ENTERPRI', '0000603817576539', '2026-02-07', 156.00, 0.00),
(16, '2026-02-08', 'DC INTL POS TXN DCC+ST 230126-EPR2603973253832', 'EPR2603973253832', '2026-02-08', 20.01, 0.00),
(17, '2026-02-09', 'NEFT CR-UTIB0001506-RAZORPAY PAYMENTS PVT LTD PAYMENT AGGREGATOR ESCR-VIKASH BAGARIA-AXISCN1246057932', 'AXISCN1246057932', '2026-02-09', 0.00, 1250.50);

-- --------------------------------------------------------

--
-- Table structure for table `category`
--

CREATE TABLE `category` (
  `c_id` int(11) NOT NULL,
  `category_name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `login`
--

CREATE TABLE `login` (
  `login_id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `login`
--

INSERT INTO `login` (`login_id`, `username`, `password`) VALUES
(1, 'vb_finance', 'vb_finance123');

-- --------------------------------------------------------

--
-- Table structure for table `sales_data`
--

CREATE TABLE `sales_data` (
  `sa_id` int(11) NOT NULL,
  `client_name` varchar(255) NOT NULL,
  `address` varchar(255) NOT NULL,
  `state` varchar(255) NOT NULL,
  `pin_code` varchar(10) NOT NULL,
  `contact_no` varchar(10) NOT NULL,
  `pan_number` varchar(12) NOT NULL,
  `email_id` varchar(255) NOT NULL,
  `kyc_verified` varchar(255) NOT NULL,
  `plan_subscribed` varchar(255) NOT NULL,
  `date_of_subscription` date NOT NULL,
  `transaction_id` varchar(255) NOT NULL,
  `plan_duration_month` int(2) NOT NULL,
  `subscription_end_date` date NOT NULL,
  `pay_made_tax_amt` decimal(10,2) NOT NULL,
  `igst` decimal(10,2) NOT NULL,
  `cgst` decimal(10,2) NOT NULL,
  `sgst` decimal(10,2) NOT NULL,
  `total_gst` decimal(10,2) NOT NULL,
  `total_payment` decimal(10,2) NOT NULL,
  `invoice_number` varchar(255) NOT NULL,
  `payment_gateway` varchar(255) NOT NULL,
  `hsh_code` varchar(255) NOT NULL,
  `gateway_charges` decimal(10,2) NOT NULL,
  `gst_on_charges` decimal(10,2) NOT NULL,
  `total_charges` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `sales_data`
--

INSERT INTO `sales_data` (`sa_id`, `client_name`, `address`, `state`, `pin_code`, `contact_no`, `pan_number`, `email_id`, `kyc_verified`, `plan_subscribed`, `date_of_subscription`, `transaction_id`, `plan_duration_month`, `subscription_end_date`, `pay_made_tax_amt`, `igst`, `cgst`, `sgst`, `total_gst`, `total_payment`, `invoice_number`, `payment_gateway`, `hsh_code`, `gateway_charges`, `gst_on_charges`, `total_charges`) VALUES
(1, 'vikash', '121, gt road', 'westbengal', '711202', '123456789', 'abcdesfg', 'test@gmail.com', 'Yes', 'MEGA OPTIONS', '0000-00-00', '1234567890', 3, '0000-00-00', 1000.00, 0.00, 90.00, 90.00, 180.00, 1180.00, 'VB/25-26/WEB/228', 'RAZORPAY', '997156', 25.37, 4.57, 29.94),
(2, 'atanu', '124/6161a.road', 'UTTAR PRADESH', '123456', '123456789', 'abcdesfg', 'test@gmail.com', 'Yes', 'EQUITY', '0000-00-00', '456789654', 1, '0000-00-00', 2000.00, 360.00, 90.00, 90.00, 360.00, 2360.00, 'VB/25-26/WEB/228', 'RAZORPAY', '', 0.00, 0.00, 0.00);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `bank_data`
--
ALTER TABLE `bank_data`
  ADD PRIMARY KEY (`b_id`);

--
-- Indexes for table `category`
--
ALTER TABLE `category`
  ADD PRIMARY KEY (`c_id`);

--
-- Indexes for table `login`
--
ALTER TABLE `login`
  ADD PRIMARY KEY (`login_id`);

--
-- Indexes for table `sales_data`
--
ALTER TABLE `sales_data`
  ADD PRIMARY KEY (`sa_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `bank_data`
--
ALTER TABLE `bank_data`
  MODIFY `b_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `category`
--
ALTER TABLE `category`
  MODIFY `c_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `login`
--
ALTER TABLE `login`
  MODIFY `login_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `sales_data`
--
ALTER TABLE `sales_data`
  MODIFY `sa_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

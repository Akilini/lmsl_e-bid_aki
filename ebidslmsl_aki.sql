-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:4306
-- Generation Time: Apr 20, 2026 at 06:31 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.1.25

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `ebidslmsl_aki`
--

-- --------------------------------------------------------

--
-- Table structure for table `awards`
--

CREATE TABLE `awards` (
  `bid_id` varchar(10) NOT NULL,
  `product_id` varchar(10) NOT NULL,
  `qty` int(5) NOT NULL,
  `status` varchar(20) NOT NULL,
  `approved_by` varchar(10) NOT NULL,
  `approved_date` date NOT NULL,
  `letter_no` varchar(50) NOT NULL,
  `comments` varchar(1000) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `awards`
--

INSERT INTO `awards` (`bid_id`, `product_id`, `qty`, `status`, `approved_by`, `approved_date`, `letter_no`, `comments`) VALUES
('01', '01', 20, 'Approved', 'Aki', '2026-02-06', '951', 'sdfkhbh');

-- --------------------------------------------------------

--
-- Table structure for table `bidders`
--

CREATE TABLE `bidders` (
  `bidder_id` varchar(10) NOT NULL,
  `company_name` varchar(100) NOT NULL,
  `address` varchar(200) NOT NULL,
  `mobile` varchar(17) NOT NULL,
  `email` varchar(200) NOT NULL,
  `website` varchar(200) NOT NULL,
  `land` varchar(17) NOT NULL,
  `fax` varchar(17) NOT NULL,
  `nature_of_the_business` varchar(50) NOT NULL,
  `business_registration_no` varchar(20) NOT NULL,
  `business_registration_copy` varchar(25) NOT NULL,
  `vat_registration_no` varchar(20) NOT NULL,
  `vat_registration_copy` varchar(25) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `bidders`
--

INSERT INTO `bidders` (`bidder_id`, `company_name`, `address`, `mobile`, `email`, `website`, `land`, `fax`, `nature_of_the_business`, `business_registration_no`, `business_registration_copy`, `vat_registration_no`, `vat_registration_copy`) VALUES
('BR00000001', 'SOCIAL SECURITY BOARD', 'RAJAGIRIYA', '+94770025478', 'info.ssb.gov.lk', 'ssb.gov.lk', '+94123654789', '+94123654789', 'PENSION', 'R516452', 'BR00000001_BR.pdf', 'A159854', 'BR00000001_VAT.pdf'),
('BR00000002', 'Asvith International Private LTD', '1st cross Street, Colombo 13.', '+94772428453', 'asvith$81@gmail.com', 'www.asvith.com', '+94114654712', '+94114654712', 'Groceries Import to Sri Lanka', 'A516452', 'BR00000002_BR.pdf', 'A159823', 'BR00000002_VAT.pdf'),
('BR00000003', 'SKT Terders PVT LTD', 'Colombo 13.', '+94778986960', 'skt.terder@gmail.com', 'www.sktterders.com', '+94113579864', '+94113579864', 'Import Hardware', 'r516482', 'BR00000003_BR.pdf', 'A753155', 'BR00000003_VAT.pdf'),
('BR00000004', 'vfy pvt ltd', 'Colombo 06', '+447932302171', 'hch@gmail.com', 'www.hchxhdthhg.com', '+447417366707', '+447417366707', 'PENSION', 'r514482', 'BR00000004_BR.pdf', 'A853157', 'BR00000004_VAT.pdf'),
('BR00000005', 'Yoka Internation PVT LTD', 'Colombo 06.', '+94778986960', 'yokainternational@gmail.com', 'www.yokainternational.com', '+94113579858', '+94113579858', 'Cosmetics', 'R516451', 'BR00000005_BR.pdf', 'A154355', 'BR00000005_VAT.pdf'),
('BR00000006', 'Aki Pvt Ltd', '22,Sutbbs Place, Colombo 05.', '+942147483647', 'akilini@gmail.com', 'www.aki.lk', '+942147483647', '+942147483647', 'mineral', 'R516452', 'BR00000006_BR.pdf', 'A853157', 'BR00000006_VAT.pdf');

-- --------------------------------------------------------

--
-- Table structure for table `bidders_bank`
--

CREATE TABLE `bidders_bank` (
  `bank_id` varchar(10) NOT NULL,
  `bidder_id` varchar(10) NOT NULL,
  `name` varchar(200) NOT NULL,
  `branch` varchar(200) NOT NULL,
  `code` varchar(20) NOT NULL,
  `swift_code` varchar(100) NOT NULL,
  `account_no` varchar(50) NOT NULL,
  `address` varchar(200) NOT NULL,
  `IBAN_no` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `bidders_bank`
--

INSERT INTO `bidders_bank` (`bank_id`, `bidder_id`, `name`, `branch`, `code`, `swift_code`, `account_no`, `address`, `IBAN_no`) VALUES
('BB00000001', 'BR00000001', 'PEOPLE\'S BANK', 'Colombo 006.', '1232', '1454', '15935757', 'High level road, Colombo 06.', '159782'),
('BB00000002', 'BR00000003', 'HSBC', 'RAJAGIRIYA', '3043', '1425', '15975345', 'Colombo 13.', '75846');

-- --------------------------------------------------------

--
-- Table structure for table `bids`
--

CREATE TABLE `bids` (
  `bid_id` varchar(20) NOT NULL,
  `tender_ref_no` varchar(20) NOT NULL,
  `bidder_id` varchar(10) NOT NULL,
  `proprietor_id` varchar(10) NOT NULL,
  `status` varchar(10) NOT NULL,
  `delivery_method` varchar(10) NOT NULL,
  `delivery_place` varchar(50) NOT NULL,
  `bid_currency` varchar(3) NOT NULL,
  `bid_valide_date` datetime NOT NULL,
  `submit_date` datetime NOT NULL,
  `certifieddocument` varchar(25) NOT NULL,
  `open_key` varchar(1000) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `bids`
--

INSERT INTO `bids` (`bid_id`, `tender_ref_no`, `bidder_id`, `proprietor_id`, `status`, `delivery_method`, `delivery_place`, `bid_currency`, `bid_valide_date`, `submit_date`, `certifieddocument`, `open_key`) VALUES
('BID/2026/00000000001', 'LMS/MKT/TDR/26/02', 'BR00000001', 'PP00000001', 'Approved', 'Delivery A', 'Ex - works Pulmoddai', '$', '2026-04-07 06:02:00', '2026-04-16 06:02:00', 'BID/2026/000000000002.pdf', '1234'),
('BID/2026/00000000002', 'LMS/MKT/TDR/26/01', 'BR00000002', 'PP00000002', 'Approved', 'Delivery A', 'Ex - works Pulmoddai', '$', '2026-04-02 05:29:00', '2026-04-19 05:29:00', 'BID/2026/00000000001.pdf', '2656');

-- --------------------------------------------------------

--
-- Table structure for table `bids_evaluation`
--

CREATE TABLE `bids_evaluation` (
  `bid_id` varchar(10) NOT NULL,
  `responsive` varchar(3) NOT NULL,
  `evaluation_score` float NOT NULL,
  `world_market_ref` varchar(1000) NOT NULL,
  `past_performance_notes` varchar(1000) NOT NULL,
  `committee_notes` varchar(1000) NOT NULL,
  `evaluated_by` varchar(10) NOT NULL,
  `evaluated_date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `bids_product`
--

CREATE TABLE `bids_product` (
  `bid_id` varchar(20) NOT NULL,
  `product_id` varchar(10) NOT NULL,
  `bank_id` varchar(10) NOT NULL,
  `qty` int(5) NOT NULL,
  `unit_price` float NOT NULL,
  `credit_Period_facility` date NOT NULL,
  `line_total` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `bids_securities`
--

CREATE TABLE `bids_securities` (
  `bid_id` varchar(20) NOT NULL,
  `security_type` varchar(20) NOT NULL,
  `amount_usd` float NOT NULL,
  `valid_from` date NOT NULL,
  `valid_date` date NOT NULL,
  `file` varchar(15) NOT NULL,
  `status` varchar(10) NOT NULL,
  `verify_date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `department`
--

CREATE TABLE `department` (
  `department_id` varchar(6) NOT NULL,
  `department_name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `department`
--

INSERT INTO `department` (`department_id`, `department_name`) VALUES
('DEPT01', 'Lanka Mineral Sands Limited'),
('DEPT02', 'Ministry of Industries'),
('DEPT03', 'Procurement'),
('DEPT04', 'Sales and Marketing'),
('DEPT05', 'Information Technology'),
('DEPT06', 'Finance');

-- --------------------------------------------------------

--
-- Table structure for table `invoices`
--

CREATE TABLE `invoices` (
  `invoices_id` varchar(10) NOT NULL,
  `bid_id` varchar(10) NOT NULL,
  `product_id` varchar(10) NOT NULL,
  `invoice_type` varchar(10) NOT NULL,
  `invoice_no` varchar(30) NOT NULL,
  `issue_date` date NOT NULL,
  `due_date` date NOT NULL,
  `subtotal_usd` float NOT NULL,
  `royalty_usd` float NOT NULL,
  `taxes_usd` float NOT NULL,
  `status` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

CREATE TABLE `payments` (
  `payments_id` varchar(10) NOT NULL,
  `invoices_id` varchar(30) NOT NULL,
  `amount_usd` float NOT NULL,
  `pay_date` date NOT NULL,
  `pay_mode` varchar(10) NOT NULL,
  `reference_no` varchar(10) NOT NULL,
  `reference_photo` varchar(15) NOT NULL,
  `enter_by` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `product`
--

CREATE TABLE `product` (
  `product_id` varchar(10) NOT NULL,
  `name` varchar(200) NOT NULL,
  `specification` varchar(500) NOT NULL,
  `briefly_specification_doc` varchar(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `product`
--

INSERT INTO `product` (`product_id`, `name`, `specification`, `briefly_specification_doc`) VALUES
('P000000001', 'Rutile', 'TiO2 – 95% - 97% Moisture – 0.50% Max HS Code 26140020', 'P000000001.pdf'),
('P000000002', 'Zircon Concentrate ', 'Zircon - 25% - 35% Moisture 2.00% Max HS Code 26151000', 'P000000002.pdf'),
('P000000003', 'ZIRCON CONCENTRATE', 'ZIRCON CONCENTRATE - SAMPLE NO: 4097/ZC 24/12/2025', 'P000000003.pdf'),
('P000000004', 'CRUDE RUTILE OVERSIZE', 'CRUDE RUTILE OVERSIZE – - SAMPLE NO: 4080/CRO 24/12/2025', 'P000000004.pdf'),
('P000000005', 'HITI ILMENITE', 'TiO2 – 95% - 97% Moisture – 0.50% Max HS Code 26140020', 'P000000005.pdf'),
('P000000006', 'Laptop', 'RAM 16.0 GB, Processor Intel(R) Core(TM) i5-10210U CPU @ 1.60GHz (2.11 GHz).', 'P000000006.pdf'),
('P000000007', 'Furniture', 'Office tables and chairs', 'P000000007.pdf');

-- --------------------------------------------------------

--
-- Table structure for table `proprietor`
--

CREATE TABLE `proprietor` (
  `proprietor_id` varchar(10) NOT NULL,
  `name` varchar(200) NOT NULL,
  `nic_passport` varchar(12) NOT NULL,
  `designation` varchar(100) NOT NULL,
  `address` varchar(200) NOT NULL,
  `mobile` int(15) NOT NULL,
  `land` int(15) NOT NULL,
  `nic_copy` varchar(25) NOT NULL,
  `bidder_id` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `proprietor`
--

INSERT INTO `proprietor` (`proprietor_id`, `name`, `nic_passport`, `designation`, `address`, `mobile`, `land`, `nic_copy`, `bidder_id`) VALUES
('PP00000001', 'Mr. Anil Gunawardana', '787542995V', 'Manager', '2nd cross Street, Colombo 07.', 712386960, 124654789, 'PP00000001.pdf', 'BR00000001'),
('PP00000002', 'Asvith', '831252775V', 'Manager', 'Colombo 04.', 745686960, 116654712, 'PP00000002.pdf', 'BR00000002'),
('PP00000003', 'SKT', '787542995V', 'Accountant', 'Colombo 13.', 778986960, 112561712, 'PP00000003.pdf', 'BR00000003'),
('PP00000004', 'grrtyddgf', '897545895V', 'Manager', 'likljsecgbtrt', 735760890, 124654789, 'PP00000004.pdf', 'BR00000005');

-- --------------------------------------------------------

--
-- Table structure for table `role`
--

CREATE TABLE `role` (
  `role_id` varchar(5) NOT NULL,
  `name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `role`
--

INSERT INTO `role` (`role_id`, `name`) VALUES
('ROL01', 'CHAIRMAN'),
('ROL02', 'SECRETARY'),
('ROL03', 'ADDITIONAL DIRECTOR GENERAL'),
('ROL04', 'ACTING DIRECTOR'),
('ROL05', 'GENERAL MANAGER'),
('ROL06', 'DEPUTY GENERAL MANAGER'),
('ROL07', 'CHIEF ACCOUNTANT'),
('ROL08', 'CHIEF CHEMIST'),
('ROL09', 'MANAGER'),
('ROL10', 'ICT OFFICER'),
('ROL11', 'PROCUREMENT OFFICER'),
('ROL12', 'SALES OFFICER'),
('ROL13', 'MANAGEMENT ASSISTANT');

-- --------------------------------------------------------

--
-- Table structure for table `staff`
--

CREATE TABLE `staff` (
  `staff_id` varchar(6) NOT NULL,
  `name` varchar(100) NOT NULL,
  `nic` varchar(12) NOT NULL,
  `role_id` varchar(5) NOT NULL,
  `mobile` int(10) NOT NULL,
  `department_id` varchar(6) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `staff`
--

INSERT INTO `staff` (`staff_id`, `name`, `nic`, `role_id`, `mobile`, `department_id`) VALUES
('ST0001', 'Mr. Gayan Wellala', '581976523V', 'ROL01', 771660890, 'DEPT01'),
('ST0002', 'Akilini', '927542995V', 'ROL10', 778986960, 'DEPT05');

-- --------------------------------------------------------

--
-- Table structure for table `tender`
--

CREATE TABLE `tender` (
  `tender_id` varchar(10) NOT NULL,
  `tender_ref_no` varchar(20) NOT NULL,
  `title` varchar(200) NOT NULL,
  `tender_type` varchar(12) NOT NULL,
  `currency` varchar(3) NOT NULL,
  `publish_date` date NOT NULL,
  `bid_open_date` datetime NOT NULL,
  `bid_close_date` datetime NOT NULL,
  `bid_validity` int(3) NOT NULL,
  `status` varchar(10) NOT NULL,
  `create_by` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tender`
--

INSERT INTO `tender` (`tender_id`, `tender_ref_no`, `title`, `tender_type`, `currency`, `publish_date`, `bid_open_date`, `bid_close_date`, `bid_validity`, `status`, `create_by`) VALUES
('T000000001', 'LMS/MKT/TDR/26/01', 'Sale of Heavy Mineral Sands', 'Sales', 'LKR', '2026-03-24', '2026-03-25 10:15:00', '2026-03-25 10:00:00', 4, 'Active', 'ST0001'),
('T000000002', 'LMS/MKT/TDR/26/02', 'Sale of Heavy Mineral Sand', 'Sales', '$', '2026-02-18', '2026-03-02 10:15:00', '2026-03-02 10:00:00', 91, 'Active', 'ST0002'),
('T000000003', 'LMSL/SUP/DPC/01/2026', 'Laptop', 'Procurement', 'LKR', '2026-04-20', '2026-04-30 10:15:00', '2026-04-30 10:00:00', 14, 'Active', 'ST0001');

-- --------------------------------------------------------

--
-- Table structure for table `tender_product`
--

CREATE TABLE `tender_product` (
  `tender_id` varchar(20) NOT NULL,
  `product_id` varchar(10) NOT NULL,
  `available_qty` int(5) NOT NULL,
  `min_qty` int(5) NOT NULL,
  `delivery_term` varchar(100) NOT NULL,
  `bid_security_usd` float NOT NULL,
  `bid_security_valid_days` int(3) NOT NULL,
  `perf_security_valid_days` int(3) NOT NULL,
  `comments` varchar(1000) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tender_product`
--

INSERT INTO `tender_product` (`tender_id`, `product_id`, `available_qty`, `min_qty`, `delivery_term`, `bid_security_usd`, `bid_security_valid_days`, `perf_security_valid_days`, `comments`) VALUES
('T000000001', 'P000000001', 20000, 500, 'Ex - works Pulmoddai', 15000, 91, 90, 'Mineral sales'),
('T000000001', 'P000000002', 20000, 1000, 'Ex - works Pulmoddai', 265, 91, 90, 'Mineral sales'),
('T000000001', 'P000000003', 10000, 500, 'Ex - works Pulmoddai', 265, 91, 90, 'Mineral sales'),
('T000000001', 'P000000004', 10000, 500, 'Ex - works Pulmoddai', 265, 91, 90, 'Mineral sales'),
('T000000001', 'P000000005', 20000, 500, 'Ex - works Pulmoddai', 265, 91, 90, 'Mineral sales'),
('T000000003', 'P000000001', 100, 10, 'No Delivery Services', 265, 91, 90, 'fhghgf');

-- --------------------------------------------------------

--
-- Table structure for table `tender_sales`
--

CREATE TABLE `tender_sales` (
  `tender_id` varchar(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `user_id` varchar(10) NOT NULL,
  `user_name` varchar(200) NOT NULL,
  `password` varchar(1000) NOT NULL,
  `usertype` varchar(20) NOT NULL,
  `attempt` int(1) NOT NULL,
  `otp` int(4) NOT NULL,
  `status` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`user_id`, `user_name`, `password`, `usertype`, `attempt`, `otp`, `status`) VALUES
('BR00000001', 'info.ssb.gov.lk', '17a99ffc63245d1297c02f54974307d1', 'Bidders', 0, 0, 'Active'),
('BR00000002', 'asvith$81@gmail.com', '06599c1e4151336882cd47927a36186c', 'Bidders', 0, 0, 'Active'),
('BR00000003', 'skt.terder@gmail.com', '9643a9d390d4c00d2b5b45de91825e93', 'Bidders', 0, 0, 'Active'),
('BR00000004', 'hch@gmail.com', '84a617af93beeb36af0240f23558e3e8', 'Bidders', 0, 0, 'Active'),
('BR00000005', 'yokainternational@gmail.com', '92885f7a22625f477cd29ea6f2c497a9', 'Bidders', 0, 0, 'Active'),
('BR00000006', 'akilini@gmail.com', '$2y$10$Wip0PTEHYihgYWcfH5VYCOEyU2a3eF2WfqchXi29ovLhZvni9fduG', 'Bidders', 0, 0, 'Active'),
('PP00000001', '787542995V', 'e10adc3949ba59abbe56e057f20f883e', 'Proprietor', 0, 0, 'Active'),
('PP00000002', '831252775V', 'e10adc3949ba59abbe56e057f20f883e', 'Proprietor', 0, 0, 'Active'),
('PP00000003', '787542995V', 'e10adc3949ba59abbe56e057f20f883e', 'Proprietor', 0, 0, 'Active'),
('PP00000004', '857557995V', 'e10adc3949ba59abbe56e057f20f883e', 'Proprietor', 0, 0, 'Active'),
('PP00000005', '921252775V', 'e10adc3949ba59abbe56e057f20f883e', 'Proprietor', 0, 0, 'Active'),
('PP00000006', '857542995V', 'e10adc3949ba59abbe56e057f20f883e', 'Proprietor', 0, 0, 'Active'),
('PP00000007', '785252775V', 'e10adc3949ba59abbe56e057f20f883e', 'Proprietor', 0, 0, 'Active'),
('ST0001', '581976523V', '6cf82ee1020caef069e753c67a97a70d', 'ROL01', 0, 3043, 'Active'),
('ST0002', '927542995V', 'f4fb329f3f9758857467008b87a5518b', 'ROL07', 0, 0, 'Active');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `bidders`
--
ALTER TABLE `bidders`
  ADD PRIMARY KEY (`bidder_id`);

--
-- Indexes for table `bidders_bank`
--
ALTER TABLE `bidders_bank`
  ADD PRIMARY KEY (`bank_id`);

--
-- Indexes for table `bids`
--
ALTER TABLE `bids`
  ADD PRIMARY KEY (`bid_id`);

--
-- Indexes for table `bids_evaluation`
--
ALTER TABLE `bids_evaluation`
  ADD PRIMARY KEY (`bid_id`);

--
-- Indexes for table `bids_product`
--
ALTER TABLE `bids_product`
  ADD PRIMARY KEY (`bid_id`,`product_id`);

--
-- Indexes for table `bids_securities`
--
ALTER TABLE `bids_securities`
  ADD PRIMARY KEY (`bid_id`);

--
-- Indexes for table `department`
--
ALTER TABLE `department`
  ADD PRIMARY KEY (`department_id`);

--
-- Indexes for table `invoices`
--
ALTER TABLE `invoices`
  ADD PRIMARY KEY (`invoices_id`);

--
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`payments_id`);

--
-- Indexes for table `product`
--
ALTER TABLE `product`
  ADD PRIMARY KEY (`product_id`);

--
-- Indexes for table `proprietor`
--
ALTER TABLE `proprietor`
  ADD PRIMARY KEY (`proprietor_id`);

--
-- Indexes for table `role`
--
ALTER TABLE `role`
  ADD PRIMARY KEY (`role_id`);

--
-- Indexes for table `staff`
--
ALTER TABLE `staff`
  ADD PRIMARY KEY (`staff_id`);

--
-- Indexes for table `tender`
--
ALTER TABLE `tender`
  ADD PRIMARY KEY (`tender_id`);

--
-- Indexes for table `tender_product`
--
ALTER TABLE `tender_product`
  ADD PRIMARY KEY (`tender_id`,`product_id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`user_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

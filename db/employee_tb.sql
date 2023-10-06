-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Sep 29, 2023 at 02:34 AM
-- Server version: 8.0.30
-- PHP Version: 8.1.10
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";
/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */
;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */
;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */
;
/*!40101 SET NAMES utf8mb4 */
;
--
-- Database: `hris_db`
--

-- --------------------------------------------------------
--
-- Table structure for table `employee_tb`
--

CREATE TABLE `employee_tb` (
  `id` int NOT NULL,
  `fname` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `mname` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `lname` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `company_code` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `empid` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `address` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `contact` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `cstatus` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `gender` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `empdob` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `empsss` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `emptin` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `emppagibig` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `empphilhealth` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `empbranch` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `department_name` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `empposition` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `empbsalary` float DEFAULT NULL,
  `otrate` int DEFAULT NULL,
  `drate` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `empdate_hired` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `emptranspo` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `empmeal` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `empinternet` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `empaccess_id` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `username` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `role` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `company_email` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `sss_amount` int DEFAULT NULL,
  `tin_amount` int DEFAULT NULL,
  `pagibig_amount` int DEFAULT NULL,
  `philhealth_amount` int DEFAULT NULL,
  `classification` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `bank_name` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `bank_number` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `emp_img_url` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `user_profile` longblob,
  `work_frequency` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `status` varchar(255) COLLATE utf8mb4_general_ci NOT NULL
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci;
--
-- Dumping data for table `employee_tb`
--

INSERT INTO `employee_tb` (
    `id`,
    `fname`,
    `mname`,
    `lname`,
    `company_code`,
    `empid`,
    `address`,
    `contact`,
    `cstatus`,
    `gender`,
    `empdob`,
    `empsss`,
    `emptin`,
    `emppagibig`,
    `empphilhealth`,
    `empbranch`,
    `department_name`,
    `empposition`,
    `empbsalary`,
    `otrate`,
    `drate`,
    `empdate_hired`,
    `emptranspo`,
    `empmeal`,
    `empinternet`,
    `empaccess_id`,
    `username`,
    `role`,
    `email`,
    `company_email`,
    `password`,
    `sss_amount`,
    `tin_amount`,
    `pagibig_amount`,
    `philhealth_amount`,
    `classification`,
    `bank_name`,
    `bank_number`,
    `emp_img_url`,
    `user_profile`,
    `work_frequency`,
    `status`
  )
VALUES (
    1,
    'Kerlon',
    'Lindog',
    'Piliz',
    '1',
    '1000',
    'Valenzuela',
    '58203483289',
    'Single',
    'Male',
    '2005-09-05',
    '',
    '',
    '',
    '',
    '1',
    '2',
    '2',
    20000,
    163,
    '1000.00',
    '2023-09-01',
    '',
    '',
    '',
    '1000',
    'Kerlon',
    'admin',
    'slowgrind670@gmail.com',
    NULL,
    '2f23fa3579f3f75175793649115c1b25',
    NULL,
    NULL,
    NULL,
    NULL,
    '1',
    NULL,
    NULL,
    NULL,
    NULL,
    NULL,
    'Active'
  ),
  (
    1001,
    'Chester',
    'kulot',
    'Vigita M',
    '1',
    '1001',
    'Valenzuela',
    '02458039495',
    'Single',
    'Male',
    '2005-09-02',
    '',
    '',
    '',
    '',
    '1',
    '2',
    '2',
    20000,
    163,
    '1000.00',
    '2023-09-01',
    '',
    '',
    '',
    '1001',
    'chesta',
    'admin',
    'slowgrind670@gmail.com',
    NULL,
    '2f23fa3579f3f75175793649115c1b25',
    NULL,
    NULL,
    NULL,
    NULL,
    '1',
    NULL,
    NULL,
    NULL,
    NULL,
    NULL,
    'Active'
  ),
  (
    1002,
    'Jojoseph',
    'mahinhin',
    'Palaspas',
    '1',
    '1002',
    'Valenzuela',
    '84573497859',
    'Single',
    'Male',
    '2005-09-01',
    '',
    '',
    '',
    '',
    '1',
    '2',
    '2',
    20000,
    163,
    '1000.00',
    '2023-09-01',
    '',
    '',
    '',
    '1002',
    'Joseph',
    'Employee',
    'slowgrind670@gmail.com',
    NULL,
    '2f23fa3579f3f75175793649115c1b25',
    NULL,
    NULL,
    NULL,
    NULL,
    '1',
    NULL,
    NULL,
    NULL,
    NULL,
    NULL,
    'Active'
  ),
   (
    1003,
    'Manfred',
    'Di Bartolomeo',
    'Palaspas',
    '4',
    '1003',
    'Valenzuela',
    '46636396925',
    'Single',
    'Male',
    '2005-09-01',
    '',
    '',
    '',
    '',
    '2',
    '3',
    '3',
    20000,
    163,
    '1000.00',
    '2023-09-01',
    '',
    '',
    '',
    '1003',
    'Manfred',
    'Employee',
    'psommerlin0@phpbb.com',
    NULL,
    '2f23fa3579f3f75175793649115c1b25',
    NULL,
    NULL,
    NULL,
    NULL,
    '4',
    NULL,
    NULL,
    NULL,
    NULL,
    NULL,
    'Active'
  ),
   (
    1004,
    'Celestine',
    'Di Bartolomeos',
    'Hamerton',
    '4',
    '1004',
    'Valenzuela',
    '10441629658',
    'Single',
    'Male',
    '2005-09-01',
    '',
    '',
    '',
    '',
    '2',
    '3',
    '3',
    20000,
    163,
    '1000.00',
    '2023-09-01',
    '',
    '',
    '',
    '1004',
    'Celestine',
    'Employee',
    'chamerton1@google.es',
    NULL,
    '2f23fa3579f3f75175793649115c1b25',
    NULL,
    NULL,
    NULL,
    NULL,
    '4',
    NULL,
    NULL,
    NULL,
    NULL,
    NULL,
    'Active'
  )
  ,
   (
    1005,
    'Prescott',
    'phargate2',
    'Hargate',
    '4',
    '1005',
    'Valenzuela',
    '30345555674',
    'Single',
    'Male',
    '2005-09-01',
    '',
    '',
    '',
    '',
    '2',
    '3',
    '3',
    20000,
    163,
    '1000.00',
    '2023-09-01',
    '',
    '',
    '',
    '1005',
    'Prescott',
    'Employee',
    'phargate2@trellian.com',
    NULL,
    '2f23fa3579f3f75175793649115c1b25',
    NULL,
    NULL,
    NULL,
    NULL,
    '4',
    NULL,
    NULL,
    NULL,
    NULL,
    NULL,
    'Active'
  ),
   (
    1006,
    'Olvan',
    'omcfaul3',
    'Mc Faul',
    '4',
    '1006',
    'Valenzuela',
    '55411052517',
    'Single',
    'Male',
    '2005-09-01',
    '',
    '',
    '',
    '',
    '2',
    '3',
    '3',
    20000,
    163,
    '1000.00',
    '2023-09-01',
    '',
    '',
    '',
    '1006',
    'Olvan',
    'Employee',
    'omcfaul3@purevolume.com',
    NULL,
    '2f23fa3579f3f75175793649115c1b25',
    NULL,
    NULL,
    NULL,
    NULL,
    '4',
    NULL,
    NULL,
    NULL,
    NULL,
    NULL,
    'Active'
  ),
   (
    1007,
    'Caterina',
    'cadamiak4',
    'Mc Adamiak',
    '4',
    '1007',
    'Valenzuela',
    '93453222070',
    'Single',
    'Male',
    '2005-09-01',
    '',
    '',
    '',
    '',
    '2',
    '3',
    '3',
    20000,
    163,
    '1000.00',
    '2023-09-01',
    '',
    '',
    '',
    '1007',
    'Caterina',
    'Employee',
    'cadamiak4@icio.us',
    NULL,
    '2f23fa3579f3f75175793649115c1b25',
    NULL,
    NULL,
    NULL,
    NULL,
    '4',
    NULL,
    NULL,
    NULL,
    NULL,
    NULL,
    'Active'
  ),
   (
    1008,
    'Liza',
    'lskitteral5',
    'Skitteral',
    '4',
    '1008',
    'Valenzuela',
    '85375910345',
    'Single',
    'Male',
    '2005-09-01',
    '',
    '',
    '',
    '',
    '2',
    '3',
    '3',
    20000,
    163,
    '1000.00',
    '2023-09-01',
    '',
    '',
    '',
    '1008',
    'Liza',
    'Employee',
    'lskitteral5@cbsnews.com',
    NULL,
    '2f23fa3579f3f75175793649115c1b25',
    NULL,
    NULL,
    NULL,
    NULL,
    '4',
    NULL,
    NULL,
    NULL,
    NULL,
    NULL,
    'Active'
  ),
   (
    1009,
    'Juliana',
    'jbernardeschi6',
    'Bernardeschi',
    '4',
    '1009',
    'Valenzuela',
    '12230223534',
    'Single',
    'Male',
    '2005-09-01',
    '',
    '',
    '',
    '',
    '2',
    '3',
    '3',
    20000,
    163,
    '1000.00',
    '2023-09-01',
    '',
    '',
    '',
    '1009',
    'Juliana',
    'Employee',
    'jbernardeschi6@ed.gov',
    NULL,
    '2f23fa3579f3f75175793649115c1b25',
    NULL,
    NULL,
    NULL,
    NULL,
    '4',
    NULL,
    NULL,
    NULL,
    NULL,
    NULL,
    'Active'
  ),
   (
    1010,
    'Carmelita',
    'cbattson7',
    'Battson',
    '4',
    '1010',
    'Valenzuela',
    '72515884483',
    'Single',
    'Male',
    '2005-09-01',
    '',
    '',
    '',
    '',
    '2',
    '3',
    '3',
    20000,
    163,
    '1000.00',
    '2023-09-01',
    '',
    '',
    '',
    '1010',
    'Carmelita',
    'Employee',
    'jcbattson7@1688.com',
    NULL,
    '2f23fa3579f3f75175793649115c1b25',
    NULL,
    NULL,
    NULL,
    NULL,
    '4',
    NULL,
    NULL,
    NULL,
    NULL,
    NULL,
    'Active'
  ),
   (
    1011,
    'Sasha',
    'sglenfield8',
    'Glenfield',
    '4',
    '1011',
    'Valenzuela',
    '72475047525',
    'Single',
    'Male',
    '2005-09-01',
    '',
    '',
    '',
    '',
    '2',
    '3',
    '3',
    20000,
    163,
    '1000.00',
    '2023-09-01',
    '',
    '',
    '',
    '1011',
    'Carmelita',
    'Employee',
    'sglenfield8@theguardian.com',
    NULL,
    '2f23fa3579f3f75175793649115c1b25',
    NULL,
    NULL,
    NULL,
    NULL,
    '4',
    NULL,
    NULL,
    NULL,
    NULL,
    NULL,
    'Active'
  );
--
-- Indexes for dumped tables
--

--
-- Indexes for table `employee_tb`
--
ALTER TABLE `employee_tb`
ADD PRIMARY KEY (`id`);
--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `employee_tb`
--
ALTER TABLE `employee_tb`
MODIFY `id` int NOT NULL AUTO_INCREMENT,
  AUTO_INCREMENT = 1003;
COMMIT;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */
;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */
;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */
;
-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 09, 2024 at 02:30 PM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `ups`
--

-- --------------------------------------------------------

--
-- Table structure for table `accountant_department_settings`
--

CREATE TABLE `accountant_department_settings` (
  `id` int(11) NOT NULL,
  `department_id` int(11) NOT NULL COMMENT 'refers to departments id ',
  `tax_name` varchar(500) NOT NULL,
  `tax_rate` decimal(10,2) NOT NULL,
  `tax_salary_from` decimal(10,2) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `accountant_fulfilled_payslips`
--

CREATE TABLE `accountant_fulfilled_payslips` (
  `id` int(11) NOT NULL,
  `employee_id` int(11) NOT NULL,
  `department_id` int(11) NOT NULL,
  `loghours_submitted_id` int(11) NOT NULL,
  `month` varchar(100) NOT NULL,
  `year` year(4) NOT NULL,
  `fulfilled_by` int(11) NOT NULL COMMENT 'user_id references to user table ',
  `payslip_file` text NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `departaments`
--

CREATE TABLE `departaments` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `employee_information`
--

CREATE TABLE `employee_information` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL COMMENT 'refers to user table',
  `department_id` int(11) NOT NULL COMMENT 'refers to departments table',
  `hour_pay` decimal(10,2) DEFAULT NULL,
  `salary` decimal(10,2) DEFAULT NULL,
  `weekly_hours` int(11) NOT NULL,
  `position` text NOT NULL,
  `bank_name` varchar(100) DEFAULT NULL,
  `bank_account_name` varchar(100) DEFAULT NULL,
  `bank_account` varchar(100) DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `employee_vacations`
--

CREATE TABLE `employee_vacations` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `date_from` date NOT NULL,
  `date_to` date NOT NULL,
  `is_paid` enum('0','1','','') NOT NULL,
  `absence_req_id` int(11) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `equipment_assignment`
--

CREATE TABLE `equipment_assignment` (
  `id` int(11) NOT NULL,
  `equipment_id` int(11) NOT NULL,
  `employee_id` int(11) NOT NULL,
  `date_given` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `equipment_items`
--

CREATE TABLE `equipment_items` (
  `id` int(11) NOT NULL,
  `type_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `serial_number` varchar(50) NOT NULL,
  `is_assigned` int(11) NOT NULL DEFAULT 0,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `equipment_type`
--

CREATE TABLE `equipment_type` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `logged_hours`
--

CREATE TABLE `logged_hours` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL COMMENT 'goes to user table',
  `start_time` time NOT NULL,
  `end_time` time NOT NULL,
  `night_hours` text NOT NULL,
  `total_hours` text NOT NULL,
  `date` date NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `logged_hours_submitted`
--

CREATE TABLE `logged_hours_submitted` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL COMMENT 'refers to user table id',
  `total_hours` int(11) NOT NULL,
  `night_hours` int(11) DEFAULT NULL,
  `overtime_hours` int(11) DEFAULT NULL,
  `month_name` varchar(30) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `is_confirmed` enum('0','1','','') NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `news_comments`
--

CREATE TABLE `news_comments` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `topic_id` int(11) NOT NULL COMMENT 'relates to news_topic it belongs to ',
  `comment` text NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `news_comments_rating`
--

CREATE TABLE `news_comments_rating` (
  `id` int(11) NOT NULL,
  `comment_id` int(11) NOT NULL COMMENT 'relates to news comments',
  `news_topic_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `agree` int(11) DEFAULT NULL COMMENT 'relates to news comments',
  `disagree` int(11) DEFAULT NULL COMMENT 'relates to news comments',
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `news_topic`
--

CREATE TABLE `news_topic` (
  `id` int(11) NOT NULL,
  `topic` varchar(100) NOT NULL,
  `text` text NOT NULL,
  `about` text NOT NULL,
  `news_image` varchar(500) DEFAULT NULL COMMENT 'uploaded image location',
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `performance_reports`
--

CREATE TABLE `performance_reports` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `employee_name` varchar(100) NOT NULL,
  `project_id` int(11) NOT NULL,
  `rating_text` text NOT NULL,
  `rating` tinyint(4) NOT NULL,
  `year` int(11) NOT NULL,
  `month` varchar(100) NOT NULL,
  `soft_deleted` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `req_absence`
--

CREATE TABLE `req_absence` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL COMMENT 'to `users` table',
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `duration` int(11) NOT NULL,
  `type` text NOT NULL,
  `reason` text NOT NULL,
  `status` text NOT NULL DEFAULT 'Sent',
  `comment` text DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `attachment` blob DEFAULT NULL,
  `approver_id` int(11) DEFAULT NULL,
  `date_approved` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL COMMENT 'name of the role'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `name`) VALUES
(1, 'Superadmin'),
(2, 'Employee'),
(3, 'Manager'),
(4, 'Accountant'),
(5, 'User');

-- --------------------------------------------------------

--
-- Table structure for table `submitted_tickets`
--

CREATE TABLE `submitted_tickets` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `ticket_title` varchar(100) NOT NULL,
  `ticket_text` text NOT NULL,
  `department_id` int(11) NOT NULL,
  `is_registered` tinyint(1) NOT NULL DEFAULT 0,
  `registered_by_user_id` int(11) DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tasks_participants`
--

CREATE TABLE `tasks_participants` (
  `id` int(11) NOT NULL,
  `project_id` int(11) NOT NULL,
  `employee_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tasks_project`
--

CREATE TABLE `tasks_project` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `department_id` int(11) NOT NULL,
  `leader_user_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tasks_status`
--

CREATE TABLE `tasks_status` (
  `id` int(11) NOT NULL,
  `statuses` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`statuses`)),
  `project_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tasks_task`
--

CREATE TABLE `tasks_task` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `made_by` int(11) NOT NULL,
  `assigned_to` int(11) DEFAULT NULL,
  `status_key` int(11) NOT NULL COMMENT 'key for retrieving values from statuses names json',
  `priority` varchar(50) NOT NULL,
  `task_points` decimal(4,1) DEFAULT NULL,
  `label` varchar(50) DEFAULT NULL,
  `is_draft` tinyint(1) DEFAULT 0,
  `project_id` int(11) NOT NULL,
  `is_completed` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tasks_task_comments`
--

CREATE TABLE `tasks_task_comments` (
  `id` int(11) NOT NULL,
  `task_id` int(11) NOT NULL COMMENT 'refers to tasks_task ID table field',
  `comment_author_id` int(11) NOT NULL COMMENT 'refers to users user_id table field',
  `comment_text` text NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `first_name` varchar(50) NOT NULL,
  `last_name` varchar(50) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone_number` varchar(15) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp(),
  `role_id` int(10) UNSIGNED NOT NULL,
  `is_writer` enum('0','1','','') NOT NULL DEFAULT '0',
  `soft_deleted` int(11) DEFAULT 0,
  `profile_picture` text NOT NULL DEFAULT 'uploads/default_pfp.png'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `vacation_points`
--

CREATE TABLE `vacation_points` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `vacation_points` decimal(10,6) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `accountant_department_settings`
--
ALTER TABLE `accountant_department_settings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fK_dept_settings_id` (`department_id`);

--
-- Indexes for table `accountant_fulfilled_payslips`
--
ALTER TABLE `accountant_fulfilled_payslips`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_acot_emp_payslip_id` (`employee_id`);

--
-- Indexes for table `departaments`
--
ALTER TABLE `departaments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `employee_information`
--
ALTER TABLE `employee_information`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_dept_id` (`department_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `employee_vacations`
--
ALTER TABLE `employee_vacations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_emp_vac_user_id` (`user_id`),
  ADD KEY `fk_emp_abs_id` (`absence_req_id`);

--
-- Indexes for table `equipment_assignment`
--
ALTER TABLE `equipment_assignment`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_eq_assignment_equip_item_id` (`equipment_id`),
  ADD KEY `fk_eq_assignment_employee_id` (`employee_id`);

--
-- Indexes for table `equipment_items`
--
ALTER TABLE `equipment_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_type_id` (`type_id`);

--
-- Indexes for table `equipment_type`
--
ALTER TABLE `equipment_type`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `logged_hours`
--
ALTER TABLE `logged_hours`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_lh_user_id` (`user_id`);

--
-- Indexes for table `logged_hours_submitted`
--
ALTER TABLE `logged_hours_submitted`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_lhs_user_id` (`user_id`);

--
-- Indexes for table `news_comments`
--
ALTER TABLE `news_comments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_topic_id` (`topic_id`);

--
-- Indexes for table `news_comments_rating`
--
ALTER TABLE `news_comments_rating`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_news_topic_id` (`news_topic_id`),
  ADD KEY `fk_news_user_id` (`user_id`),
  ADD KEY `fk_news_comment_rating_id` (`comment_id`);

--
-- Indexes for table `news_topic`
--
ALTER TABLE `news_topic`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `performance_reports`
--
ALTER TABLE `performance_reports`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_perf_user_id` (`user_id`),
  ADD KEY `fk_perf_proj_id` (`project_id`);

--
-- Indexes for table `req_absence`
--
ALTER TABLE `req_absence`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_users_id` (`user_id`),
  ADD KEY `fk_absence_approver_id` (`approver_id`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `submitted_tickets`
--
ALTER TABLE `submitted_tickets`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`,`registered_by_user_id`),
  ADD KEY `fk_st_dept_id` (`department_id`),
  ADD KEY `fk_st_reg_user_id` (`registered_by_user_id`);

--
-- Indexes for table `tasks_participants`
--
ALTER TABLE `tasks_participants`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_tasks_participants_project_id` (`project_id`),
  ADD KEY `fk_tasks_participants_emp_id` (`employee_id`);

--
-- Indexes for table `tasks_project`
--
ALTER TABLE `tasks_project`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_dept_id` (`department_id`),
  ADD KEY `fk_projleaduser_id` (`leader_user_id`);

--
-- Indexes for table `tasks_status`
--
ALTER TABLE `tasks_status`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_status_proj_id` (`project_id`);

--
-- Indexes for table `tasks_task`
--
ALTER TABLE `tasks_task`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_project_id` (`project_id`),
  ADD KEY `fk_made_by` (`made_by`);

--
-- Indexes for table `tasks_task_comments`
--
ALTER TABLE `tasks_task_comments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_tasks_comment_author_id` (`comment_author_id`),
  ADD KEY `fk_tasks_task_comment_id` (`task_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `phone_number` (`phone_number`),
  ADD KEY `fk_user_role_id` (`role_id`);

--
-- Indexes for table `vacation_points`
--
ALTER TABLE `vacation_points`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_vp` (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `accountant_department_settings`
--
ALTER TABLE `accountant_department_settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `accountant_fulfilled_payslips`
--
ALTER TABLE `accountant_fulfilled_payslips`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `departaments`
--
ALTER TABLE `departaments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `employee_information`
--
ALTER TABLE `employee_information`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `employee_vacations`
--
ALTER TABLE `employee_vacations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `equipment_assignment`
--
ALTER TABLE `equipment_assignment`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `equipment_items`
--
ALTER TABLE `equipment_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `equipment_type`
--
ALTER TABLE `equipment_type`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `logged_hours`
--
ALTER TABLE `logged_hours`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `logged_hours_submitted`
--
ALTER TABLE `logged_hours_submitted`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `news_comments`
--
ALTER TABLE `news_comments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `news_comments_rating`
--
ALTER TABLE `news_comments_rating`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `news_topic`
--
ALTER TABLE `news_topic`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `performance_reports`
--
ALTER TABLE `performance_reports`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `req_absence`
--
ALTER TABLE `req_absence`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `submitted_tickets`
--
ALTER TABLE `submitted_tickets`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tasks_participants`
--
ALTER TABLE `tasks_participants`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tasks_project`
--
ALTER TABLE `tasks_project`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tasks_status`
--
ALTER TABLE `tasks_status`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tasks_task`
--
ALTER TABLE `tasks_task`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tasks_task_comments`
--
ALTER TABLE `tasks_task_comments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `vacation_points`
--
ALTER TABLE `vacation_points`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `accountant_department_settings`
--
ALTER TABLE `accountant_department_settings`
  ADD CONSTRAINT `fK_dept_settings_id` FOREIGN KEY (`department_id`) REFERENCES `departaments` (`id`);

--
-- Constraints for table `accountant_fulfilled_payslips`
--
ALTER TABLE `accountant_fulfilled_payslips`
  ADD CONSTRAINT `fk_acot_emp_payslip_id` FOREIGN KEY (`employee_id`) REFERENCES `employee_information` (`id`);

--
-- Constraints for table `employee_vacations`
--
ALTER TABLE `employee_vacations`
  ADD CONSTRAINT `fk_emp_abs_id` FOREIGN KEY (`absence_req_id`) REFERENCES `req_absence` (`id`),
  ADD CONSTRAINT `fk_emp_vac_user_id` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `equipment_assignment`
--
ALTER TABLE `equipment_assignment`
  ADD CONSTRAINT `fk_eq_assignment_employee_id` FOREIGN KEY (`employee_id`) REFERENCES `employee_information` (`id`),
  ADD CONSTRAINT `fk_eq_assignment_equip_item_id` FOREIGN KEY (`equipment_id`) REFERENCES `equipment_items` (`id`);

--
-- Constraints for table `equipment_items`
--
ALTER TABLE `equipment_items`
  ADD CONSTRAINT `fk_type_id` FOREIGN KEY (`type_id`) REFERENCES `equipment_type` (`id`);

--
-- Constraints for table `logged_hours`
--
ALTER TABLE `logged_hours`
  ADD CONSTRAINT `fk_lh_user_id` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `logged_hours_submitted`
--
ALTER TABLE `logged_hours_submitted`
  ADD CONSTRAINT `fk_lhs_user_id` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `news_comments`
--
ALTER TABLE `news_comments`
  ADD CONSTRAINT `fk_topic_id` FOREIGN KEY (`topic_id`) REFERENCES `news_topic` (`id`);

--
-- Constraints for table `news_comments_rating`
--
ALTER TABLE `news_comments_rating`
  ADD CONSTRAINT `fk_news_comment_rating_id` FOREIGN KEY (`comment_id`) REFERENCES `news_comments` (`id`),
  ADD CONSTRAINT `fk_news_topic_id` FOREIGN KEY (`news_topic_id`) REFERENCES `news_topic` (`id`),
  ADD CONSTRAINT `fk_news_user_id` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `performance_reports`
--
ALTER TABLE `performance_reports`
  ADD CONSTRAINT `fk_perf_proj_id` FOREIGN KEY (`project_id`) REFERENCES `tasks_project` (`id`),
  ADD CONSTRAINT `fk_perf_user_id` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `req_absence`
--
ALTER TABLE `req_absence`
  ADD CONSTRAINT `fk_absence_approver_id` FOREIGN KEY (`approver_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `fk_users_id` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `submitted_tickets`
--
ALTER TABLE `submitted_tickets`
  ADD CONSTRAINT `fk_st_dept_id` FOREIGN KEY (`department_id`) REFERENCES `departaments` (`id`),
  ADD CONSTRAINT `fk_st_reg_user_id` FOREIGN KEY (`registered_by_user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `fk_st_user_id` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `tasks_participants`
--
ALTER TABLE `tasks_participants`
  ADD CONSTRAINT `fk_tasks_participants_emp_id` FOREIGN KEY (`employee_id`) REFERENCES `employee_information` (`id`),
  ADD CONSTRAINT `fk_tasks_participants_project_id` FOREIGN KEY (`project_id`) REFERENCES `tasks_project` (`id`);

--
-- Constraints for table `tasks_project`
--
ALTER TABLE `tasks_project`
  ADD CONSTRAINT `fk_proj_dept_id` FOREIGN KEY (`department_id`) REFERENCES `departaments` (`id`),
  ADD CONSTRAINT `fk_projleaduser_id` FOREIGN KEY (`leader_user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `tasks_status`
--
ALTER TABLE `tasks_status`
  ADD CONSTRAINT `fk_status_proj_id` FOREIGN KEY (`project_id`) REFERENCES `tasks_project` (`id`);

--
-- Constraints for table `tasks_task`
--
ALTER TABLE `tasks_task`
  ADD CONSTRAINT `fk_task_made_by` FOREIGN KEY (`made_by`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `fk_task_proj_id` FOREIGN KEY (`project_id`) REFERENCES `tasks_project` (`id`);

--
-- Constraints for table `tasks_task_comments`
--
ALTER TABLE `tasks_task_comments`
  ADD CONSTRAINT `fk_tasks_comment_author_id` FOREIGN KEY (`comment_author_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `fk_tasks_task_comment_id` FOREIGN KEY (`task_id`) REFERENCES `tasks_task` (`id`);

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `fk_user_role_id` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`);

--
-- Constraints for table `vacation_points`
--
ALTER TABLE `vacation_points`
  ADD CONSTRAINT `fk_vp` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

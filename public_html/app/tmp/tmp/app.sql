-- phpMyAdmin SQL Dump
-- version 5.1.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3307
-- Generation Time: Apr 29, 2023 at 06:54 AM
-- Server version: 10.3.14-MariaDB
-- PHP Version: 8.0.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `edutest`
--

-- --------------------------------------------------------

--
-- Table structure for table `advertisements`
--

DROP TABLE IF EXISTS `advertisements`;
CREATE TABLE IF NOT EXISTS `advertisements` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) DEFAULT NULL,
  `photo` varchar(100) DEFAULT NULL,
  `ordering` int(11) DEFAULT NULL,
  `url` varchar(255) DEFAULT NULL,
  `url_type` varchar(8) DEFAULT NULL,
  `url_target` varchar(6) DEFAULT NULL,
  `status` varchar(7) DEFAULT 'Active',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `configurations`
--

DROP TABLE IF EXISTS `configurations`;
CREATE TABLE IF NOT EXISTS `configurations` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `organization_name` varchar(255) DEFAULT NULL,
  `domain_name` varchar(255) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `meta_title` text DEFAULT NULL,
  `meta_keyword` text DEFAULT NULL,
  `meta_content` text DEFAULT NULL,
  `timezone` varchar(100) DEFAULT NULL,
  `author` varchar(255) DEFAULT NULL,
  `sms_notification` tinyint(1) DEFAULT NULL,
  `email_notification` tinyint(1) DEFAULT NULL,
  `guest_login` tinyint(1) DEFAULT NULL,
  `front_end` tinyint(1) DEFAULT NULL,
  `slides` tinyint(4) DEFAULT NULL,
  `translate` tinyint(4) DEFAULT 0,
  `paid_exam` tinyint(4) DEFAULT 1,
  `leader_board` tinyint(1) DEFAULT 1,
  `math_editor` tinyint(1) DEFAULT 0,
  `certificate` tinyint(1) DEFAULT 1,
  `contact` text DEFAULT NULL,
  `email_contact` text DEFAULT NULL,
  `currency` int(11) DEFAULT NULL,
  `photo` varchar(100) DEFAULT NULL,
  `signature` varchar(100) DEFAULT NULL,
  `favicon` varchar(100) DEFAULT NULL,
  `date_format` varchar(25) DEFAULT NULL,
  `exam_expiry` int(11) NOT NULL DEFAULT 1,
  `exam_feedback` tinyint(1) NOT NULL DEFAULT 1,
  `tolrance_count` int(1) DEFAULT NULL,
  `min_limit` int(11) DEFAULT NULL,
  `max_limit` int(11) DEFAULT NULL,
  `captcha_type` tinyint(4) DEFAULT NULL,
  `dir_type` tinyint(4) DEFAULT NULL,
  `language` varchar(6) DEFAULT NULL,
  `panel1` tinyint(1) DEFAULT 1,
  `panel2` tinyint(1) DEFAULT 1,
  `panel3` tinyint(1) DEFAULT 1,
  `ads` tinyint(1) DEFAULT 1,
  `testimonial` tinyint(1) DEFAULT 1,
  `free_package` tinyint(1) DEFAULT 1,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  `powerdby` varchar(100) NOT NULL,
  `powerdlink` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `configurations`
--

INSERT INTO `configurations` (`id`, `name`, `organization_name`, `domain_name`, `email`, `meta_title`, `meta_keyword`, `meta_content`, `timezone`, `author`, `sms_notification`, `email_notification`, `guest_login`, `front_end`, `slides`, `translate`, `paid_exam`, `leader_board`, `math_editor`, `certificate`, `contact`, `email_contact`, `currency`, `photo`, `signature`, `favicon`, `date_format`, `exam_expiry`, `exam_feedback`, `tolrance_count`, `min_limit`, `max_limit`, `captcha_type`, `dir_type`, `language`, `panel1`, `panel2`, `panel3`, `ads`, `testimonial`, `free_package`, `created`, `modified`, `powerdby`, `powerdlink`) VALUES
(1, 'Edu Expression Elite', 'Zuxus Business Solution', 'http://127.0.0.1:88/eduelite7', 'no-reply@nowhere.com', 'Edu Expression Elite', 'Exam Software, Exam Application, Edu Expression Elite', 'Edu Expression Elite is a leading exam application.', 'Asia/Kolkata', 'Exam Solution', 0, 1, 0, 1, 1, 0, 0, 1, 0, 1, '0000-0000~info@eduexpression.com~http://facebook.com', 'Phone : 0000000000 Email : demo@demo.com', 21, 'logo-website.fw.png', '871d157c9c20f5f1a7ae1ae0dfe2c41a.jpg', 'd62f5a69f996d563f146a52436d80bbe.png', 'd,m,Y,h,i,s,A,-,:', 0, 1, 3, 30, 500, 0, 1, 'en', 1, 1, 1, 1, 1, 1, '2014-04-08 20:56:04', '2017-06-06 14:24:52', 'Eduexpression.com', 'https://eduexpression.com/');

-- --------------------------------------------------------

--
-- Table structure for table `contents`
--

DROP TABLE IF EXISTS `contents`;
CREATE TABLE IF NOT EXISTS `contents` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `link_name` varchar(255) DEFAULT NULL,
  `page_name` varchar(255) DEFAULT NULL,
  `is_url` varchar(8) DEFAULT 'Internal',
  `url` varchar(255) DEFAULT NULL,
  `url_target` varchar(6) DEFAULT NULL,
  `main_content` longtext DEFAULT NULL,
  `page_url` varchar(255) DEFAULT NULL,
  `icon` varchar(40) DEFAULT NULL,
  `parent_id` int(11) DEFAULT NULL,
  `ordering` int(11) DEFAULT NULL,
  `views` int(11) DEFAULT 1,
  `sel_name` varchar(100) DEFAULT NULL,
  `published` varchar(11) DEFAULT 'Published',
  `meta_title` text DEFAULT NULL,
  `meta_keyword` text DEFAULT NULL,
  `meta_content` text DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `contents`
--

INSERT INTO `contents` (`id`, `link_name`, `page_name`, `is_url`, `url`, `url_target`, `main_content`, `page_url`, `icon`, `parent_id`, `ordering`, `views`, `sel_name`, `published`, `meta_title`, `meta_keyword`, `meta_content`, `created`, `modified`) VALUES
(1, 'Home', 'Home', 'Page', 'Home', '_self', '', 'Home', 'fa fa-home', 0, 1, 16, NULL, 'Published', '', '', '', '2016-12-05 18:11:19', '2017-03-20 19:29:58'),
(2, 'About', 'About', 'Internal', '', '_self', '', 'About', 'fa fa-globe', 0, 2, 9, NULL, 'Published', '', '', '', '2016-12-05 13:59:12', '2017-03-20 19:29:58'),
(3, 'About Us', 'About Us', 'Internal', '', '_self', '<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nulla non molestie magna. Phasellus luctus, erat quis efficitur lacinia, magna massa rutrum libero, fermentum cursus enim erat vel lorem. Vestibulum quis faucibus risus. Cras egestas mauris sed nulla maximus cursus. Integer varius leo sed metus egestas fringilla. Praesent mattis, eros non consectetur ultrices, diam felis dictum nisl, non bibendum nibh lorem ut justo. Nulla orci nunc, aliquam ac finibus sit amet, porttitor vitae risus. Maecenas bibendum felis mi, vel euismod eros rutrum vitae. Vivamus suscipit nulla scelerisque libero venenatis placerat. Phasellus vitae egestas odio. Integer non justo nisl. Vivamus tincidunt est eu nisi semper dignissim. Nam rhoncus sapien quis diam ultrices, quis malesuada ex euismod.</p>\r\n<p>Vivamus vel porta lacus. Donec a dui risus. Nunc eget mi in diam faucibus molestie. Duis dictum dolor sit amet semper consequat. Nunc et facilisis orci, sed vestibulum lacus. Morbi metus sapien, lobortis et placerat non, finibus ut mauris. Aenean tristique, ex sagittis tristique congue, enim eros congue nulla, sed placerat erat felis eu urna. Fusce porttitor tortor vitae metus pulvinar, nec bibendum tortor aliquet. Vivamus id nisi malesuada, facilisis sem nec, aliquam massa. Integer et diam ac velit iaculis sollicitudin. Pellentesque placerat viverra nibh, sed congue nibh maximus sit amet.</p>\r\n<p>Duis fringilla pulvinar nulla, eget condimentum arcu accumsan quis. Curabitur at pulvinar libero, at interdum elit. Vivamus sed dui non sapien aliquet tincidunt. Phasellus ut ligula sem. Cras elit ante, varius at elementum nec, vestibulum pharetra mauris. Nulla molestie ultrices lectus, et pellentesque nisl finibus ut. Proin vel massa vitae sem pharetra ultrices vel ut risus. Morbi erat mi, aliquam et venenatis nec, sollicitudin vitae felis. Phasellus lectus purus, venenatis in sapien at, malesuada tincidunt magna. Aliquam eu nunc vel quam consequat fringilla eu non sapien. In est risus, gravida at libero ut, elementum molestie sem. Curabitur cursus nulla nec metus cursus, non convallis purus aliquam. Aliquam erat volutpat. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Maecenas dignissim nibh id blandit facilisis.</p>\r\n<p>Proin iaculis vehicula dolor, id commodo ex ultricies nec. Donec quis est vitae purus auctor rutrum. Nulla convallis velit id tellus finibus faucibus. Cras dignissim justo non libero tempor cursus. Etiam libero tellus, sagittis tempor diam quis, dignissim pretium lacus. Cras ac ipsum ac tortor ornare luctus. Praesent dignissim metus ultricies nisl feugiat, id convallis velit maximus. Curabitur et interdum tellus. Proin quis bibendum sapien. Maecenas sit amet massa at lorem aliquet tincidunt. Maecenas leo felis, dictum non neque et, tempus blandit sem. Mauris sit amet mi purus. Sed non odio sit amet dolor scelerisque facilisis. Cras sollicitudin fermentum ipsum. Fusce dictum, ipsum a auctor suscipit, tortor nisl cursus mi, eget viverra dolor est in diam.</p>\r\n<p>Vestibulum efficitur vel ligula a vestibulum. Donec condimentum porta bibendum. In lobortis odio ut suscipit vulputate. Proin tempor dapibus ornare. Maecenas auctor convallis ullamcorper. In elementum sed dolor vel cursus. Praesent at tempor turpis. Praesent interdum dapibus sapien id vulputate. In maximus finibus lorem in condimentum. Proin nec sapien sit amet libero placerat vestibulum eget in turpis.</p>', 'About-Us', '', 2, 3, 148, NULL, 'Published', '<p>About Us</p>', '', '', '2016-12-05 14:14:13', '2017-03-25 00:57:00'),
(4, 'Profile', 'Profile', 'Internal', '', '_self', '<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer sit amet ligula metus. Nulla sagittis orci id ultricies elementum. Proin maximus tortor urna, ac egestas quam placerat nec. Praesent ultrices neque tincidunt lectus malesuada, facilisis commodo odio luctus. Pellentesque blandit, sem quis mollis tincidunt, enim lectus scelerisque diam, et egestas sem nunc ac turpis. Aliquam faucibus purus ut velit facilisis condimentum. Nam rhoncus aliquam leo vitae tempor. Nulla magna purus, vestibulum eget cursus scelerisque, eleifend in metus. Phasellus pretium elit sapien, sit amet sagittis metus facilisis ac. Nunc dictum commodo ante ac sagittis. Vestibulum non ligula elementum, aliquam metus id, dapibus magna. Aenean lacinia, urna nec blandit fringilla, lectus lectus lacinia enim, nec iaculis lectus eros vel lacus. Proin tristique metus ac felis dictum pretium. Nulla non sollicitudin mi, a tincidunt arcu.</p>\r\n<p>Phasellus accumsan, tortor non bibendum elementum, mi diam scelerisque mi, imperdiet imperdiet tellus sem ut ligula. Mauris et risus efficitur nunc viverra ornare. Aenean semper lectus in nisl tincidunt, sit amet pharetra nibh efficitur. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Maecenas lobortis elit et felis vehicula malesuada non eu lorem. Suspendisse quis porttitor magna. Mauris erat enim, blandit eu sodales nec, commodo nec lectus. Ut eget ligula in sapien bibendum auctor. Pellentesque ac iaculis diam. Vestibulum quam sapien, rhoncus sit amet dapibus egestas, ultricies elementum est. Quisque congue leo eu purus vehicula rhoncus. Suspendisse ac neque et velit auctor tempor. Cras tempus ligula sit amet sagittis commodo.</p>\r\n<p>Mauris efficitur libero sit amet tortor tincidunt, at faucibus ex semper. Morbi non lorem posuere, ullamcorper ante a, auctor ante. Etiam pretium blandit risus sed fringilla. Proin et dignissim eros. Pellentesque at commodo lectus, quis blandit leo. Aenean rutrum lacus non congue tempus. In rutrum augue a enim auctor, vel sodales purus condimentum. Fusce sit amet est neque. Fusce rutrum maximus turpis.</p>', 'Profile', '', 2, 4, 15, NULL, 'Published', 'Profile', '', '', '2016-12-06 17:43:20', '2017-03-20 19:30:01'),
(5, 'Register', '', 'Page', 'Registers', '_self', '', 'Registers', 'fa fa-user', 0, 6, 5, NULL, 'Published', '', '', '', '2016-12-06 11:11:09', '2017-03-20 19:29:58'),
(6, 'Login', '', 'Page', 'crm/Users', '_self', '', 'Login', 'fa fa-lock', 0, 7, 1, NULL, 'Published', '', '', '', '2016-12-06 16:10:52', '2017-03-20 19:29:58'),
(7, 'Packages', '', 'Page', 'Packages/index', '_self', '', 'Packages', 'fa fa-shopping-cart', 0, 5, 1, NULL, 'Published', '', '', '', '2017-03-20 19:29:33', '2017-03-20 19:29:58'),
(8, 'Schedules', '', 'Page', 'Schedules', '_self', '', 'Schedules', 'fa fa-calendar', 0, 6, 1, NULL, 'Published', '', '', '', '2018-09-13 12:04:21', '2018-09-13 12:04:43'),
(9, 'App Contact Us', 'Edu Contact Us', 'Internal', '', '_self', 'Get in touch with is with our Customer Care learn at <strong>+91 9800000000</strong> <br />or drop a mail at <strong>info@eduexpression.com</strong>', 'App-Contact-Us', '', 0, 8, 3, NULL, 'Unpublished', '', '', '', '2020-12-07 19:34:26', '2020-12-07 19:51:47');

-- --------------------------------------------------------

--
-- Table structure for table `coupons`
--

DROP TABLE IF EXISTS `coupons`;
CREATE TABLE IF NOT EXISTS `coupons` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `amount` decimal(10,2) DEFAULT NULL,
  `discount_type` varchar(10) DEFAULT NULL,
  `min_amount` decimal(10,2) DEFAULT NULL,
  `code` varchar(15) DEFAULT NULL,
  `coupon_no` int(11) DEFAULT NULL,
  `per_customer` int(11) DEFAULT NULL,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `status` varchar(8) DEFAULT 'Active',
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `coupons_students`
--

DROP TABLE IF EXISTS `coupons_students`;
CREATE TABLE IF NOT EXISTS `coupons_students` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `student_id` int(11) DEFAULT NULL,
  `coupon_id` int(11) DEFAULT NULL,
  `redeem_date` datetime DEFAULT NULL,
  `redeem_ip` varchar(50) DEFAULT NULL,
  `session_id` varchar(100) DEFAULT NULL,
  `status` char(1) DEFAULT '0',
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `student_id` (`student_id`),
  KEY `coupon_id` (`coupon_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `currencies`
--

DROP TABLE IF EXISTS `currencies`;
CREATE TABLE IF NOT EXISTS `currencies` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) DEFAULT NULL,
  `short` varchar(3) DEFAULT NULL,
  `photo` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `currencies`
--

INSERT INTO `currencies` (`id`, `name`, `short`, `photo`) VALUES
(1, 'Australia Dollar AUD', 'AUD', '64238c6d767ab034b04c4681295567a0.gif'),
(2, 'Brunei Darussalam Dollar BND', 'BND', '53e34059e7bfe4db945404e901c4f396.gif'),
(3, 'Cambodia Riel KHR', 'KHR', 'aaa57dd0012641cdee2c8d6484db8238.gif'),
(4, 'China Yuan Renminbi CNY ', 'CNY', '5586a267c542d0f49b6c22c5c978bf23.gif'),
(5, 'Hong Kong Dollar HKD', 'HKD', '200ec0145292d85b380d8c4f570f9aa9.gif'),
(6, 'India Rupee INR', 'INR', '537f17a76864d11438d25ff5af7641a5.gif'),
(7, 'Indonesia Rupiah IDR', 'IDR', '6d27b2f196ce9d74b10d12111d9838b0.gif'),
(8, 'Japan Yen JPY', 'JPY', '3a7f86a61af62ddab4737f3df6db4807.gif'),
(9, 'Korea (North) Won KPW', 'KPW', 'cc0ad4a7ba48bedd9cf57bc4125fc2c9.gif'),
(10, 'Korea (South) Won KRW', 'KRW', '28fdcdac33f7429afe6bce2e08dd47c2.gif'),
(11, 'Laos Kip LAK', 'LAK', 'f72da580f617ee32683202aeee564df0.gif'),
(12, 'Malaysia Ringgit MYR', 'MYR', 'e86af0a98bf7398c27a5ad30319d82ad.gif'),
(13, 'Nigeria Naira NGN', 'NGN', '2cdb9ceeae309e948c6bd0a90e30ffec.gif'),
(14, 'Pakistan Rupee PKR', 'PKR', 'bac3525bb97f15f806a74d248f71d6b2.gif'),
(15, 'Philippines Peso PHP', 'PHP', 'c46c38e2701d3c3bd6ee442c93befd04.gif'),
(16, 'Singapore Dollar SGD', 'SGD', '2c1e20836f56700b13a08477216a61fb.gif'),
(17, 'Sri Lanka Rupee LKR', 'LKR', '38bb6c10813d0a1eb9c878bcea2b7570.gif'),
(18, 'Taiwan New Dollar TWD', 'TWD', 'a558976f34bf485cb72f61656595536c.gif'),
(19, 'Thailand Baht THB', 'THB', '3c3bcc74de1fd038ec2d7e0dfe2965bf.gif'),
(20, 'United Kingdom Pound GBP', 'GBP', 'df773c6ce35993089139c888ec5a3210.gif'),
(21, 'United States Dollar USD', 'USD', 'ef1e801ee13715b41e55c16886597878.gif'),
(22, 'Viet Nam Dong VND', 'VND', '5a5b143e1685239abd85f0b367d4669b.gif');

-- --------------------------------------------------------

--
-- Table structure for table `diffs`
--

DROP TABLE IF EXISTS `diffs`;
CREATE TABLE IF NOT EXISTS `diffs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `diff_level` varchar(15) DEFAULT NULL,
  `type` char(1) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `diffs`
--

INSERT INTO `diffs` (`id`, `diff_level`, `type`) VALUES
(1, 'Easy', 'E'),
(2, 'Medium', 'M'),
(3, 'Hard', 'H');

-- --------------------------------------------------------

--
-- Table structure for table `emailsettings`
--

DROP TABLE IF EXISTS `emailsettings`;
CREATE TABLE IF NOT EXISTS `emailsettings` (
  `id` int(11) NOT NULL,
  `type` varchar(10) CHARACTER SET latin1 DEFAULT NULL,
  `host` varchar(100) CHARACTER SET latin1 DEFAULT NULL,
  `username` varchar(100) CHARACTER SET latin1 DEFAULT NULL,
  `password` varchar(100) CHARACTER SET latin1 DEFAULT NULL,
  `port` varchar(10) CHARACTER SET latin1 DEFAULT NULL,
  `tls` tinyint(4) DEFAULT 0,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `emailsettings`
--

INSERT INTO `emailsettings` (`id`, `type`, `host`, `username`, `password`, `port`, `tls`) VALUES
(1, 'Mail', NULL, NULL, NULL, NULL, 0);

-- --------------------------------------------------------

--
-- Table structure for table `emailtemplates`
--

DROP TABLE IF EXISTS `emailtemplates`;
CREATE TABLE IF NOT EXISTS `emailtemplates` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `status` varchar(11) DEFAULT 'Published',
  `type` varchar(3) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `emailtemplates`
--

INSERT INTO `emailtemplates` (`id`, `name`, `description`, `status`, `type`) VALUES
(1, 'Student Registration', '<p>Hi, {#studentName#}</p><p>Your signup email: {#email#}</p><p>Your password: {#password#}</p><p>Please click the following link to finish up registration:</p><p><a href=\"{#url#}\" target=\"_blank\">{#url#}</a></p><p><strong>Note: If the link does not open directly, please copy and paste the url into your internet browser.</strong></p><p>Verification Code: {#code#}</p><p>Sincerely,</p><p>{#siteName#}</p><p>{#siteEmailContact#}</p>', 'Published', 'SRN'),
(2, 'Re-send Verification', '<p>Hi, {#studentName#}</p><p>Your signup email: {#email#}</p><p>Please click the following link to finish up registration:</p><p><a href=\"{#url#}\" target=\"_blank\">{#url#}</a></p><p><strong>Note: If the link does not open directly, please copy and paste the url into your internet browser.</strong></p><p>Verification Code: {#code#}</p><p>Sincerely,</p><p>{#siteName#}</p><p>{#siteEmailContact#}</p>', 'Published', 'RVN'),
(4, 'Student Forgot Password', '<p>Dear {#studentName#},</p><p>Please click the following link to finish forgot password:</p><p><a href=\"{#url#}\" target=\"_blank\">{#url#}</a></p><p><strong>Note: If the link does not open directly, please copy and paste the url into your internet browser.</strong></p><p>Verification Code: {#code#}</p><p>Sincerely,</p><p>{#siteName#}</p><p>{#siteEmailContact#}</p>', 'Published', 'SFP'),
(5, 'Admin Forgot Password', '<p>Dear {#name#},</p><p>Please click the following link to finish forgot password:</p><p><a href=\"{#url#}\" target=\"_blank\">{#url#}</a></p><p><strong>Note: If the link does not open directly, please copy and paste the url into your internet browser.</strong></p><p>Verification Code: {#code#}</p><p>Sincerely,</p><p>{#siteName#}</p><p>{#siteEmailContact#}</p>', 'Published', 'AFP'),
(6, 'Admin Forgot Username', '<p>Dear {#name#},</p><p>You have forgot User Name. your username is {#userName#}</p><p>Sincerely,</p><p>{#siteName#}</p><p>{#siteEmailContact#}</p>', 'Published', 'AFU'),
(7, 'Student Login Credentials', '<p>Dear {#studentName#},</p><p>Congratulations! Your {#siteName#} account is now active.</p><p>Email Address : {#email#}</p><p>Password: {#password#}</p><p>If you need, you can reset your password at any time.</p><p>To get started, log on:<a href=\"{#url#}\" target=\"_blank\">{#url#}</a></p><p>If you have any questions or need assistance, please contact us.</p><p> </p><p>Best Regards,</p><p>{#siteName#}</p><p>{#siteEmailContact#}</p>', 'Published', 'SLC'),
(8, 'User Login Credentials', '<p>Dear {#name#},</p><p>Congratulations! Your {#siteName#} account is now active.</p><p>Email Address : {#email#}</p><p>Username : {#userName#}</p><p>Password: {#password#}</p><p>If you need, you can reset your password at any time.</p><p>To get started, log on:<a href=\"{#url#}\" target=\"_blank\">{#url#}</a></p><p>If you have any questions or need assistance, please contact us.</p><p> </p><p>Best Regards,</p><p>{#siteName#}</p><p>{#siteEmailContact#}</p>', 'Published', 'ULC'),
(9, 'Exam Activation', '<p>Dear Student,</p><p>Exam Name {#examName#} Type {#type#} is active and start on {#startDate#} end on {#endDate#}</p><p>Sincerely,</p><p>{#siteName#}</p><p>{#siteEmailContact#}</p>', 'Unpublished', 'EAN'),
(10, 'Exam Finalized', '<p>Dear {#studentName#},</p><p>Name: {#examName#}</p><p>Result: {#result#}</p><p>Rank: {#rank#}</p><p>Obtained Marks: {#obtainedMarks#}</p><p>Question Attempt: {#questionAttempt#}</p><p>Time Taken: {#timeTaken#}</p><p>Percentage: {#percent#}</p><p> </p><p>Sincerely,</p><p>{#siteName#}</p><p>{#siteEmailContact#}</p>', 'Unpublished', 'EFD'),
(11, 'Exam Result', '<p>Dear {#studentName#},</p><p>Name: {#examName#}</p><p>Result: {#result#}</p><p>Obtained Marks: {#obtainedMarks#}</p><p>Question Attempt: {#questionAttempt#}</p><p>Time Taken: {#timeTaken#}</p><p>Percentage: {#percent#} %</p><p> </p><p>Sincerely,</p><p>{#siteName#}</p><p>{#siteEmailContact#}</p>', 'Published', 'ERT'),
(12, 'Package Purchased', '<p>Dear {#studentName#},</p><p>We thank you for choosing {#siteName#} for your career, professional and educational needs.</p><p>Your package details are as follows:-</p><p><strong>Total Amount: {#currency#} {#totalAmount#}</strong></p><p><strong>Coupon Discount: {#currency#} {#couponDiscount#}</strong></p><p><strong>Net Amount : {#currency#} {#netAmount#}</strong></p><p><strong>Transaction ID: {#transactionId#}</strong></p><p>{#packageDetail#}</p><p>Sincerely,</p><p>{#siteName#}</p><p>{#siteEmailContact#}</p>', 'Published', 'PPD');

-- --------------------------------------------------------

--
-- Table structure for table `exams`
--

DROP TABLE IF EXISTS `exams`;
CREATE TABLE IF NOT EXISTS `exams` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `instruction` text DEFAULT NULL,
  `syllabus` text DEFAULT NULL,
  `duration` int(11) DEFAULT NULL,
  `start_date` datetime DEFAULT NULL,
  `end_date` datetime DEFAULT NULL,
  `passing_percent` int(11) DEFAULT NULL,
  `negative_marking` varchar(3) DEFAULT NULL,
  `attempt_count` int(11) DEFAULT NULL,
  `declare_result` varchar(3) DEFAULT 'Yes',
  `finish_result` char(1) DEFAULT '0',
  `ques_random` char(1) DEFAULT '0',
  `paid_exam` char(1) DEFAULT '0',
  `browser_tolrance` char(1) DEFAULT '1',
  `instant_result` char(1) NOT NULL DEFAULT '0',
  `option_shuffle` char(1) DEFAULT '1',
  `amount` decimal(10,2) DEFAULT NULL,
  `status` varchar(10) DEFAULT 'Inactive',
  `type` varchar(12) DEFAULT NULL,
  `multi_language` char(1) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `expiry` int(11) DEFAULT 0,
  `finalized_time` datetime DEFAULT NULL,
  `calculator` char(1) DEFAULT '0',
  `exam_mode` char(1) DEFAULT 'D',
  `pause_exam` char(1) DEFAULT '0',
  `math_editor_type` char(1) DEFAULT '0',
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `exams_packages`
--

DROP TABLE IF EXISTS `exams_packages`;
CREATE TABLE IF NOT EXISTS `exams_packages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `package_id` int(11) NOT NULL,
  `exam_id` int(11) NOT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `package_id` (`package_id`),
  KEY `exam_id` (`exam_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `exams_subjects`
--

DROP TABLE IF EXISTS `exams_subjects`;
CREATE TABLE IF NOT EXISTS `exams_subjects` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `exam_id` int(11) NOT NULL,
  `subject_id` int(11) NOT NULL,
  `duration` int(11) NOT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `exam_id` (`exam_id`),
  KEY `subject_id` (`subject_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- --------------------------------------------------------

--
-- Table structure for table `exam_feedbacks`
--

DROP TABLE IF EXISTS `exam_feedbacks`;
CREATE TABLE IF NOT EXISTS `exam_feedbacks` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `exam_result_id` int(11) NOT NULL,
  `comment1` varchar(255) DEFAULT NULL,
  `comment2` varchar(255) DEFAULT NULL,
  `comment3` varchar(255) DEFAULT NULL,
  `comments` mediumtext DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `exam_result_id` (`exam_result_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `exam_groups`
--

DROP TABLE IF EXISTS `exam_groups`;
CREATE TABLE IF NOT EXISTS `exam_groups` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `exam_id` int(11) NOT NULL,
  `group_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `exam_id` (`exam_id`),
  KEY `group_id` (`group_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `exam_maxquestions`
--

DROP TABLE IF EXISTS `exam_maxquestions`;
CREATE TABLE IF NOT EXISTS `exam_maxquestions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `exam_id` int(11) NOT NULL,
  `subject_id` int(11) NOT NULL,
  `max_question` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `exam_id` (`exam_id`),
  KEY `subject_id` (`subject_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `exam_orders`
--

DROP TABLE IF EXISTS `exam_orders`;
CREATE TABLE IF NOT EXISTS `exam_orders` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `student_id` int(11) NOT NULL,
  `exam_id` int(11) NOT NULL,
  `payment_id` int(11) DEFAULT NULL,
  `date` date DEFAULT NULL,
  `expiry_date` date DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `exam_id` (`exam_id`),
  KEY `student_id` (`student_id`),
  KEY `payment_id` (`payment_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `exam_preps`
--

DROP TABLE IF EXISTS `exam_preps`;
CREATE TABLE IF NOT EXISTS `exam_preps` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `exam_id` int(11) NOT NULL,
  `subject_id` int(11) NOT NULL,
  `ques_no` int(11) DEFAULT NULL,
  `type` varchar(10) DEFAULT NULL,
  `level` varchar(10) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `exam_id` (`exam_id`),
  KEY `subject_id` (`subject_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `exam_questions`
--

DROP TABLE IF EXISTS `exam_questions`;
CREATE TABLE IF NOT EXISTS `exam_questions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `exam_id` int(11) NOT NULL,
  `question_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `exam_id` (`exam_id`),
  KEY `question_id` (`question_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `exam_results`
--

DROP TABLE IF EXISTS `exam_results`;
CREATE TABLE IF NOT EXISTS `exam_results` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `exam_id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `start_time` datetime DEFAULT NULL,
  `end_time` datetime DEFAULT NULL,
  `attempt_time` datetime DEFAULT NULL,
  `total_test_time` int(11) DEFAULT NULL,
  `test_time` int(11) DEFAULT NULL,
  `pause_time` datetime DEFAULT NULL,
  `total_question` int(11) DEFAULT NULL,
  `total_attempt` int(11) DEFAULT NULL,
  `total_answered` int(11) DEFAULT NULL,
  `total_marks` decimal(5,2) DEFAULT NULL,
  `obtained_marks` decimal(5,2) DEFAULT NULL,
  `result` varchar(10) DEFAULT NULL,
  `percent` decimal(5,2) DEFAULT NULL,
  `finalized_time` datetime DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `exam_id` (`exam_id`),
  KEY `student_id` (`student_id`),
  KEY `exam_id_2` (`exam_id`,`student_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `exam_stats`
--

DROP TABLE IF EXISTS `exam_stats`;
CREATE TABLE IF NOT EXISTS `exam_stats` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `exam_result_id` int(11) NOT NULL,
  `exam_id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `question_id` int(11) NOT NULL,
  `tsubject_id` int(11) DEFAULT NULL,
  `tsubject_time` int(11) DEFAULT NULL,
  `is_section` char(1) DEFAULT '0',
  `ques_no` int(11) DEFAULT NULL,
  `options` varchar(30) DEFAULT NULL,
  `attempt_time` datetime DEFAULT NULL,
  `opened` char(1) DEFAULT '0',
  `answered` char(1) DEFAULT '0',
  `review` char(1) DEFAULT '0',
  `option_selected` varchar(15) DEFAULT NULL,
  `answer` text DEFAULT NULL,
  `true_false` varchar(5) DEFAULT NULL,
  `fill_blank` text DEFAULT NULL,
  `correct_answer` text DEFAULT NULL,
  `marks` decimal(5,2) DEFAULT NULL,
  `marks_obtained` decimal(5,2) DEFAULT NULL,
  `ques_status` char(1) DEFAULT NULL,
  `closed` char(1) DEFAULT '0',
  `user_id` int(11) DEFAULT NULL,
  `checking_time` datetime DEFAULT NULL,
  `time_taken` int(11) DEFAULT NULL,
  `bookmark` char(1) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `exam_id` (`exam_id`),
  KEY `student_id` (`student_id`),
  KEY `question_id` (`question_id`),
  KEY `exam_result_id` (`exam_result_id`),
  KEY `exam_id_2` (`exam_id`,`ques_no`),
  KEY `exam_id_3` (`exam_id`,`student_id`,`answered`,`closed`),
  KEY `exam_id_4` (`exam_id`,`student_id`,`ques_no`,`closed`),
  KEY `exam_result_id_2` (`exam_result_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `exam_warns`
--

DROP TABLE IF EXISTS `exam_warns`;
CREATE TABLE IF NOT EXISTS `exam_warns` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `exam_result_id` int(11) NOT NULL,
  `created` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `exam_result_id` (`exam_result_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `groups`
--

DROP TABLE IF EXISTS `groups`;
CREATE TABLE IF NOT EXISTS `groups` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `group_name` varchar(255) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `group_name` (`group_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `helpcontents`
--

DROP TABLE IF EXISTS `helpcontents`;
CREATE TABLE IF NOT EXISTS `helpcontents` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `link_title` varchar(255) DEFAULT NULL,
  `link_desc` longtext DEFAULT NULL,
  `status` varchar(8) DEFAULT 'Active',
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `homesections`
--

DROP TABLE IF EXISTS `homesections`;
CREATE TABLE IF NOT EXISTS `homesections` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `section` varchar(30) CHARACTER SET utf8 DEFAULT NULL,
  `sections_heading` varchar(300) CHARACTER SET utf8 DEFAULT NULL,
  `sections_content` longtext CHARACTER SET utf8 DEFAULT NULL,
  `sections_img` varchar(300) CHARACTER SET utf8 DEFAULT NULL,
  `content` varchar(5) CHARACTER SET utf8 DEFAULT 'Yes',
  `image` varchar(5) CHARACTER SET utf8 DEFAULT 'Yes',
  `published` varchar(15) CHARACTER SET utf8 DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `homesections`
--

INSERT INTO `homesections` (`id`, `section`, `sections_heading`, `sections_content`, `sections_img`, `content`, `image`, `published`) VALUES
(1, 'Header Top image', ' Edu Expression', '<p>The most Powerful Examination Engine on Envato. Try Edu Expression today. Visit EduExpression.com for more information or details.</p><p><a class=\"btn btn-success h-btn mar-b8 mar-h4 js-tb-signup-anchor\" href=\"/Registers\">Get Started For Free</a></p>', 'ee8fc908ad2c41c023a0755b4c6486b3.jpg', '1', '1', 'Published'),
(2, 'Packages', 'The most powerful Online examination System ', 'Edu Expression Enables you to conduct flexible online examination system with ease. Powerful system, dozen of settings, native mobile app and secure frame work make the software stand different in crowd.', '', '1', '1', 'Published'),
(3, 'AppTab1', 'Go mobile with Native App', '<p>Losing traffic due to no Mobile App ? Try Edu Expression with Native Mobile app for Android to enable a smooth Examination experiences on Mobile Devices and small screen. White Label Mobile App comes with your brand name and your organization listing at Google Play Store at just $65 extra. Try now today. </p>\r\n<div class=\"play-store-url\"><img src=\"img/Uploads/google-play.png\" alt=\"\" width=\"186\" height=\"56\" /></div>', '98394e57b2eba1036ed3d80856b2f1c5.jpg', '1', '1', 'Published'),
(4, 'AppTab2', 'Go mobile with Native App', 'Losing traffic due to no Mobile App ? Try Edu Expression with Native Mobile app for Android to enable a smooth Examination experiences on Mobile Devices and small screen. White Label Mobile App comes with your brand name and your organization listing at Google Play Store at just $65 extra. Try now today. \r\n<div class=\"play-store-url\">\r\n                    <a class=\"app-link\" href=\"#\" target=\"_blank\" rel=\"noopener\">\r\n                    <img class=\"img-responsive\" style=\"width: 150px\"  alt=\"App on Google Playstore\" src=\"img/Uploads/google-play.png\" data-was-processed=\"true\">\r\n                    </a>\r\n                  </div>', 'd75e77890389d3ea8ab24048ae591ee0.jpg', '1', '1', 'Published'),
(5, 'Footer image', 'Start Your Exam Preparation Now!', '<p>PRACTICE, ANALYZE AND IMPROVE!</p><p><a class=\"btn btn-success h-btn mar-b8 mar-h4 js-tb-signup-anchor\" href=\"/Registers/\">Get Started For Free</a></p>', '05aebf5cfc01f0cac8f9fdc1b65b4452.jpg', '1', '1', 'Published');

-- --------------------------------------------------------

--
-- Table structure for table `languages`
--

DROP TABLE IF EXISTS `languages`;
CREATE TABLE IF NOT EXISTS `languages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `value1` varchar(100) DEFAULT NULL,
  `value2` varchar(100) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `languages`
--

INSERT INTO `languages` (`id`, `name`, `value1`, `value2`, `created`, `modified`) VALUES
(1, 'English', 'True', 'False', '2017-03-29 16:36:55', '2017-03-29 16:36:55'),
(2, 'Hindi', 'सत्य', 'असत्य', '2017-03-29 12:07:06', '2017-03-29 12:07:06');

-- --------------------------------------------------------

--
-- Table structure for table `mails`
--

DROP TABLE IF EXISTS `mails`;
CREATE TABLE IF NOT EXISTS `mails` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `to_email` varchar(100) DEFAULT NULL,
  `from_email` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `subject` varchar(255) DEFAULT NULL,
  `message` longtext DEFAULT NULL,
  `date` datetime DEFAULT NULL,
  `status` varchar(5) DEFAULT 'Live',
  `type` varchar(10) DEFAULT 'Unread',
  `mail_type` varchar(4) DEFAULT 'To',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `news`
--

DROP TABLE IF EXISTS `news`;
CREATE TABLE IF NOT EXISTS `news` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `news_title` varchar(255) DEFAULT NULL,
  `news_desc` longtext DEFAULT NULL,
  `page_url` varchar(255) DEFAULT NULL,
  `meta_title` text DEFAULT NULL,
  `meta_keyword` text DEFAULT NULL,
  `meta_content` text DEFAULT NULL,
  `status` varchar(7) DEFAULT 'Active',
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `packages`
--

DROP TABLE IF EXISTS `packages`;
CREATE TABLE IF NOT EXISTS `packages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `amount` decimal(10,2) DEFAULT NULL,
  `show_amount` decimal(10,2) DEFAULT NULL,
  `package_type` char(1) DEFAULT NULL,
  `photo` varchar(100) DEFAULT NULL,
  `expiry_days` int(11) DEFAULT NULL,
  `status` varchar(8) DEFAULT 'Active',
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `packages_payments`
--

DROP TABLE IF EXISTS `packages_payments`;
CREATE TABLE IF NOT EXISTS `packages_payments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `payment_id` int(11) DEFAULT NULL,
  `package_id` int(11) DEFAULT NULL,
  `student_id` int(11) DEFAULT NULL,
  `price` decimal(10,2) DEFAULT NULL,
  `quantity` int(11) DEFAULT NULL,
  `amount` decimal(10,2) DEFAULT NULL,
  `date` date DEFAULT NULL,
  `expiry_date` date DEFAULT NULL,
  `status` varchar(10) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `payment_id` (`payment_id`),
  KEY `package_id` (`package_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `pages`
--

DROP TABLE IF EXISTS `pages`;
CREATE TABLE IF NOT EXISTS `pages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `model_name` varchar(100) DEFAULT NULL,
  `page_name` varchar(100) DEFAULT NULL,
  `controller_name` varchar(100) DEFAULT NULL,
  `action_name` varchar(100) DEFAULT NULL,
  `icon` varchar(30) DEFAULT NULL,
  `parent_id` int(1) DEFAULT NULL,
  `ordering` int(11) DEFAULT NULL,
  `published` varchar(3) DEFAULT 'Yes',
  `sel_name` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=50 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `pages`
--

INSERT INTO `pages` (`id`, `model_name`, `page_name`, `controller_name`, `action_name`, `icon`, `parent_id`, `ordering`, `published`, `sel_name`) VALUES
(1, 'Dashboard', 'Dashboard', 'Dashboards', 'index', 'fa fa-dashboard fa-fw', 0, 1, 'Yes', NULL),
(2, 'Subject', 'Subjects', 'Subjects', 'index', 'fa fa-book fa-fw', 0, 6, 'Yes', NULL),
(3, 'Student', 'Students', 'Students', 'index', 'fa fa-graduation-cap fa-fw', 0, 13, 'Yes', 'Iestudents'),
(4, 'Exam', 'Exams', 'Exams', 'index', 'fa fa-list-alt fa-fw', 0, 11, 'Yes', 'Attemptedpapers,Addquestions'),
(5, 'Exam', 'Attempted Papers', 'Attemptedpapers', 'index', '', 4, 6, 'No', NULL),
(6, 'Result', 'Results', 'Results', 'index', 'fa fa-trophy fa-fw', 0, 12, 'Yes', NULL),
(7, 'Configuration', 'Configurations', NULL, NULL, 'fa fa-wrench fa-fw', 0, 17, 'Yes', NULL),
(8, 'Help', 'Help', 'Helps', 'index', 'fa fa-support fa-fw', 0, 20, 'No', NULL),
(9, 'User', 'Users', 'Users', 'index', 'fa fa-user fa-fw', 0, 4, 'Yes', NULL),
(10, 'Group', 'Groups', 'Groups', 'index', 'fa fa-users fa-fw', 0, 3, 'Yes', NULL),
(11, 'Content', 'Contents', NULL, NULL, 'fa fa-newspaper-o fa-fw', 0, 18, 'Yes', NULL),
(12, 'Content', 'Home Slides', 'Slides', 'index', '', 11, 3, 'Yes', NULL),
(13, 'Configuration', 'Organisation Logo', 'Weblogos', 'index', '', 7, 4, 'Yes', NULL),
(14, 'Content', 'News', 'News', 'index', '', 11, 1, 'Yes', NULL),
(15, 'Exam', 'Add Question', 'Addquestions', 'index', NULL, 4, 99, 'No', NULL),
(16, 'Content', 'Help Content', 'Helpcontents', 'index', '', 11, 5, 'Yes', NULL),
(17, 'Question', 'Questions', 'Questions', 'index', 'fa fa-question fa-fw', 0, 10, 'Yes', 'Iequestions'),
(18, 'Question', 'Question Import/Export', 'Iequestions', 'index', '', 17, 99, 'No', NULL),
(19, 'Configuration', 'Payment Settings', 'PaymentSettings', 'index', '', 7, 2, 'Yes', NULL),
(20, 'Mailbox', 'Mailbox', 'Mails', 'index', 'fa fa-envelope fa-fw', 0, 16, 'Yes', NULL),
(21, 'Student', 'Student Import/Export', 'Iestudents', 'index', '', 3, 99, 'No', NULL),
(22, 'Configuration', 'General', 'Configurations', 'index', NULL, 7, 1, 'Yes', NULL),
(23, 'Configuration', 'Currency', 'Currencies', 'index', '', 7, 3, 'Yes', NULL),
(24, 'Content', 'Testimonial', 'Testimonials', 'index', NULL, 11, 6, 'Yes', NULL),
(25, 'Content', 'Advertisement', 'Advertisements', 'index', NULL, 11, 7, 'Yes', NULL),
(26, 'Content', 'Pages', 'Contents', 'index', NULL, 11, 2, 'Yes', NULL),
(27, 'Configuration', 'Certificate Signature', 'Signatures', 'index', NULL, 7, 5, 'Yes', NULL),
(28, 'Configuration', 'Diffculty Level', 'Diffs', 'index', NULL, 7, 6, 'Yes', NULL),
(29, 'Configuration', 'Question Type', 'qtypes', 'index', '', 7, 7, 'Yes', NULL),
(30, 'Configuration', 'Menu Names', 'Menunames', 'index', '', 7, 8, 'Yes', NULL),
(31, 'Email & SMS', 'Email & SMS', NULL, NULL, 'fa fa-shield', 0, 19, 'Yes', NULL),
(32, 'Email & SMS', 'E-Mail Settings', 'Emailsettings', 'index', '', 31, 1, 'Yes', NULL),
(33, 'Email & SMS', 'Email Templates', 'Emailtemplates', 'index', NULL, 31, 2, 'Yes', NULL),
(34, 'Email & SMS', 'Send Emails', 'Sendemails', 'index', NULL, 31, 3, 'Yes', NULL),
(35, 'Email & SMS', 'SMS Settings', 'Smssettings', 'index', '', 31, 4, 'Yes', NULL),
(36, 'Email & SMS', 'SMS Templates', 'Smstemplates', NULL, 'index', 31, 5, 'Yes', NULL),
(37, 'Email & SMS', 'Send Sms', 'Sendsms', 'index', '', 31, 6, 'Yes', NULL),
(38, 'Content', 'Seo', 'Seos', 'index', NULL, 11, 8, 'Yes', NULL),
(39, 'Exam', 'Download Result', 'Downloadresults', 'index', NULL, 4, 99, 'No', NULL),
(40, 'Package', 'Packages', 'Packages', 'index', 'fa fa-shopping-cart', 0, 5, 'Yes', NULL),
(41, 'Salesreport', 'Sales Report', 'Salesreports', 'index', 'fa fa-money', 0, 15, 'Yes', NULL),
(42, 'Configuration', 'Question Language', 'Languages', 'index', NULL, 7, 2, 'Yes', NULL),
(43, 'Configuration', 'Add Question Language', 'QuestionsLangs', 'index', NULL, 7, 99, 'No', NULL),
(44, 'Passage', 'Passage', 'Passages', 'index', 'fa fa-bars', 0, 9, 'Yes', NULL),
(45, 'Coupon', 'Coupons', 'Coupons', 'index', 'fa fa-magic', 0, 14, 'Yes', NULL),
(46, 'Topic', 'Topic', 'Topics', 'index', 'fa fa-file', 0, 7, 'Yes', NULL),
(47, 'Sub Topic', 'Sub Topic', 'Stopics', 'index', 'fa fa-file', 0, 8, 'Yes', NULL),
(48, 'Payment', 'Transaction Report', 'TransactionReports', 'index', 'fa fa-shopping-cart', 0, 2, 'Yes', 'transaction_reports'),
(49, 'Content', 'Home Content', 'Homesections', 'index', '', 11, 4, 'Yes', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `page_rights`
--

DROP TABLE IF EXISTS `page_rights`;
CREATE TABLE IF NOT EXISTS `page_rights` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `page_id` int(11) NOT NULL,
  `ugroup_id` int(11) NOT NULL,
  `save_right` int(1) DEFAULT NULL,
  `update_right` int(1) DEFAULT NULL,
  `view_right` int(1) DEFAULT NULL,
  `search_right` int(1) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `page_id` (`page_id`),
  KEY `ugroup_id` (`ugroup_id`)
) ENGINE=InnoDB AUTO_INCREMENT=36 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `page_rights`
--

INSERT INTO `page_rights` (`id`, `page_id`, `ugroup_id`, `save_right`, `update_right`, `view_right`, `search_right`) VALUES
(1, 1, 3, NULL, NULL, 1, NULL),
(2, 31, 3, NULL, NULL, 1, NULL),
(3, 34, 3, NULL, NULL, 1, NULL),
(4, 37, 3, NULL, NULL, 1, NULL),
(5, 4, 3, NULL, NULL, 1, NULL),
(6, 5, 3, NULL, NULL, 1, NULL),
(7, 15, 3, NULL, NULL, 1, NULL),
(8, 8, 3, NULL, NULL, 1, NULL),
(9, 20, 3, NULL, NULL, 1, NULL),
(10, 30, 3, NULL, NULL, 1, NULL),
(11, 19, 3, NULL, NULL, 1, NULL),
(12, 17, 3, NULL, NULL, 1, NULL),
(13, 18, 3, NULL, NULL, 1, NULL),
(14, 29, 3, NULL, NULL, 1, NULL),
(15, 6, 3, NULL, NULL, 1, NULL),
(16, 3, 3, NULL, NULL, 1, NULL),
(17, 21, 3, NULL, NULL, 1, NULL),
(18, 2, 3, NULL, NULL, 1, NULL),
(19, 1, 2, NULL, NULL, 1, NULL),
(20, 4, 2, NULL, NULL, 1, NULL),
(21, 5, 2, NULL, NULL, 1, NULL),
(22, 15, 2, NULL, NULL, 1, NULL),
(23, 8, 2, NULL, NULL, 1, NULL),
(24, 17, 2, NULL, NULL, 1, NULL),
(25, 18, 2, NULL, NULL, 1, NULL),
(26, 6, 2, NULL, NULL, 1, NULL),
(27, 3, 2, NULL, NULL, 1, NULL),
(28, 21, 2, NULL, NULL, 1, NULL),
(29, 2, 2, NULL, NULL, 1, NULL),
(30, 39, 2, NULL, NULL, 1, NULL),
(31, 39, 3, NULL, NULL, 1, NULL),
(32, 43, 2, NULL, NULL, 1, NULL),
(33, 43, 3, NULL, NULL, 1, NULL),
(34, 44, 2, NULL, NULL, 1, NULL),
(35, 44, 3, NULL, NULL, 1, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `passages`
--

DROP TABLE IF EXISTS `passages`;
CREATE TABLE IF NOT EXISTS `passages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `passage` text DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

DROP TABLE IF EXISTS `payments`;
CREATE TABLE IF NOT EXISTS `payments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `student_id` int(11) NOT NULL,
  `transaction_id` varchar(20) DEFAULT NULL,
  `amount` decimal(18,2) DEFAULT NULL,
  `coupon_id` int(11) DEFAULT NULL,
  `coupon_amount` decimal(10,2) DEFAULT NULL,
  `remarks` varchar(100) DEFAULT NULL,
  `token` varchar(50) DEFAULT NULL,
  `correlation_id` varchar(50) DEFAULT NULL,
  `timestamp` varchar(50) DEFAULT NULL,
  `status` varchar(10) DEFAULT NULL,
  `date` datetime DEFAULT NULL,
  `name` varchar(13) DEFAULT NULL,
  `type` varchar(3) DEFAULT NULL,
  `payments_from` varchar(10) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `student_id` (`student_id`),
  KEY `coupon_id` (`coupon_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `payment_settings`
--

DROP TABLE IF EXISTS `payment_settings`;
CREATE TABLE IF NOT EXISTS `payment_settings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(255) DEFAULT NULL,
  `password` varchar(100) DEFAULT NULL,
  `signature` varchar(255) DEFAULT NULL,
  `sandbox_mode` tinyint(1) DEFAULT 0,
  `name` varchar(255) DEFAULT NULL,
  `type` varchar(3) DEFAULT NULL,
  `gateway_url` varchar(255) DEFAULT NULL,
  `authorization` varchar(255) DEFAULT NULL,
  `published` varchar(3) DEFAULT 'Yes',
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `payment_settings`
--

INSERT INTO `payment_settings` (`id`, `username`, `password`, `signature`, `sandbox_mode`, `name`, `type`, `gateway_url`, `authorization`, `published`, `created`, `modified`) VALUES
(1, '', '', '', 0, 'Pay Pal', 'PPL', NULL, NULL, 'No', NULL, '2017-03-28 22:54:32'),
(2, '', '', '', 0, 'CC AVENUE', 'CAE', 'https://secure.ccavenue.com', NULL, 'No', NULL, '2017-03-28 22:54:42'),
(3, '', '', '', 0, 'PAYU MONEY', 'PME', '', NULL, 'No', NULL, '2017-06-06 12:18:10');

-- --------------------------------------------------------

--
-- Table structure for table `qtypes`
--

DROP TABLE IF EXISTS `qtypes`;
CREATE TABLE IF NOT EXISTS `qtypes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `question_type` varchar(20) DEFAULT NULL,
  `type` char(1) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `qtypes`
--

INSERT INTO `qtypes` (`id`, `question_type`, `type`) VALUES
(1, 'Objective', 'M'),
(2, 'True / False', 'T'),
(3, 'Fill in the blanks', 'F'),
(4, 'Subjective', 'S');

-- --------------------------------------------------------

--
-- Table structure for table `questions`
--

DROP TABLE IF EXISTS `questions`;
CREATE TABLE IF NOT EXISTS `questions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `qtype_id` int(11) NOT NULL,
  `subject_id` int(11) NOT NULL,
  `topic_id` int(11) DEFAULT NULL,
  `stopic_id` int(11) DEFAULT NULL,
  `diff_id` int(11) NOT NULL,
  `passage_id` int(11) DEFAULT NULL,
  `question` text DEFAULT NULL,
  `option1` text DEFAULT NULL,
  `option2` text DEFAULT NULL,
  `option3` text DEFAULT NULL,
  `option4` text DEFAULT NULL,
  `option5` text DEFAULT NULL,
  `option6` text DEFAULT NULL,
  `marks` decimal(5,2) DEFAULT NULL,
  `negative_marks` decimal(5,2) DEFAULT NULL,
  `hint` text DEFAULT NULL,
  `explanation` text DEFAULT NULL,
  `answer` varchar(15) DEFAULT NULL,
  `true_false` varchar(5) DEFAULT NULL,
  `fill_blank` text DEFAULT NULL,
  `status` varchar(3) DEFAULT 'Yes',
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `qtype_id` (`qtype_id`),
  KEY `subject_id` (`subject_id`),
  KEY `diff_id` (`diff_id`),
  KEY `topic_id` (`topic_id`),
  KEY `stopic_id` (`stopic_id`),
  KEY `qtype_id_2` (`qtype_id`,`subject_id`,`topic_id`,`stopic_id`,`diff_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `questions_langs`
--

DROP TABLE IF EXISTS `questions_langs`;
CREATE TABLE IF NOT EXISTS `questions_langs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `question_id` int(11) NOT NULL,
  `language_id` int(11) DEFAULT NULL,
  `question` text DEFAULT NULL,
  `option1` text DEFAULT NULL,
  `option2` text DEFAULT NULL,
  `option3` text DEFAULT NULL,
  `option4` text DEFAULT NULL,
  `option5` text DEFAULT NULL,
  `option6` text DEFAULT NULL,
  `hint` text DEFAULT NULL,
  `explanation` text DEFAULT NULL,
  `fill_blank` text DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `question_id` (`question_id`),
  KEY `language_id` (`language_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `question_groups`
--

DROP TABLE IF EXISTS `question_groups`;
CREATE TABLE IF NOT EXISTS `question_groups` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `question_id` int(11) NOT NULL,
  `group_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `question_id` (`question_id`),
  KEY `group_id` (`group_id`),
  KEY `group_id_2` (`group_id`),
  KEY `question_id_2` (`question_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `seos`
--

DROP TABLE IF EXISTS `seos`;
CREATE TABLE IF NOT EXISTS `seos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `controller` varchar(100) DEFAULT NULL,
  `action` varchar(100) DEFAULT NULL,
  `meta_title` text DEFAULT NULL,
  `meta_keyword` text DEFAULT NULL,
  `meta_content` text DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `seos`
--

INSERT INTO `seos` (`id`, `controller`, `action`, `meta_title`, `meta_keyword`, `meta_content`) VALUES
(1, 'Registers', 'index', 'New Student Register', '', ''),
(2, 'Users', 'login', 'Student Login Panel', '', ''),
(3, 'Forgots', 'password', 'Forgot Password', '', ''),
(4, 'Forgots', 'presetcode', 'Verification Code', '', ''),
(5, 'Forgots', 'reset', 'Reset Password', '', ''),
(6, 'Emailverifications', 'index', 'Email Verification', '', ''),
(7, 'Emailverifications', 'resend', 'Re-Send Email Verification', '', ''),
(8, 'Packages', 'index', 'Packages', '', ''),
(9, 'Checkouts', 'index', 'Checkout', '', ''),
(10, 'Carts', 'view', 'Shopping Cart', '', '');

-- --------------------------------------------------------

--
-- Table structure for table `slides`
--

DROP TABLE IF EXISTS `slides`;
CREATE TABLE IF NOT EXISTS `slides` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `slide_name` varchar(255) DEFAULT NULL,
  `ordering` int(11) DEFAULT NULL,
  `photo` varchar(100) DEFAULT NULL,
  `dir` varchar(255) DEFAULT NULL,
  `status` varchar(7) DEFAULT 'Active',
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  `heading` varchar(200) NOT NULL,
  `content` longtext NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `slides`
--

INSERT INTO `slides` (`id`, `slide_name`, `ordering`, `photo`, `dir`, `status`, `created`, `modified`, `heading`, `content`) VALUES
(1, 'Slide1', 1, '9a7906ffc41e8beaafb1bf2a2b982d9a.jpg', '', 'Active', '2014-12-19 14:42:37', '2017-12-14 05:53:45', 'Flawless Exam Experience ', '<p class=\"text\">Clear Exam interface with Categories , Subject and Question Panel. Ability to switch from one Subject to another and one question to another. Color legends to track your progress. Essential features such as Browser switching features, language change option are also available. <br /> <a class=\"btn h-btn mar-v16 btn-success js-tb-signup-anchor\" href=\"/Registers\">Get started</a></p>');

-- --------------------------------------------------------

--
-- Table structure for table `smssettings`
--

DROP TABLE IF EXISTS `smssettings`;
CREATE TABLE IF NOT EXISTS `smssettings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `api` varchar(255) DEFAULT NULL,
  `username` varchar(100) DEFAULT NULL,
  `password` varchar(100) DEFAULT NULL,
  `senderid` varchar(100) DEFAULT NULL,
  `husername` varchar(100) DEFAULT NULL,
  `hpassword` varchar(100) DEFAULT NULL,
  `hsenderid` varchar(100) DEFAULT NULL,
  `hmobile` varchar(100) DEFAULT NULL,
  `hmessage` varchar(100) DEFAULT NULL,
  `dlt_template_name` varchar(100) DEFAULT NULL,
  `others` varchar(255) DEFAULT NULL,
  `post_type` varchar(4) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `smssettings`
--

INSERT INTO `smssettings` (`id`, `api`, `username`, `password`, `senderid`, `husername`, `hpassword`, `hsenderid`, `hmobile`, `hmessage`, `dlt_template_name`, `others`, `post_type`) VALUES
(1, '', '', '', '', '', '', '', '', '', '', '', 'GET');

-- --------------------------------------------------------

--
-- Table structure for table `smstemplates`
--

DROP TABLE IF EXISTS `smstemplates`;
CREATE TABLE IF NOT EXISTS `smstemplates` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `status` varchar(11) DEFAULT 'Published',
  `type` varchar(3) DEFAULT NULL,
  `dlt_template_value` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `smstemplates`
--

INSERT INTO `smstemplates` (`id`, `name`, `description`, `status`, `type`, `dlt_template_value`) VALUES
(1, 'Student Registration', 'Hi, {#studentName#} Email: {#email#} Password: {#password#} Website: {#url#} Verification Code: {#code#} Sincerely, {#siteName#}', 'Published', 'SRN', NULL),
(2, 'Re-send Verification', 'Hi, {#studentName#} Email: {#email#} Website: {#url#} Verification Code: {#code#} Sincerely, {#siteName#}', 'Published', 'RVN', NULL),
(4, 'Student Forgot Password', 'Dear {#studentName#}, Website: {#url#} Verification Code: {#code#} Sincerely, {#siteName#}', 'Published', 'SFP', NULL),
(5, 'Admin Forgot Password', 'Dear {#name#}, Website: {#url#} Verification Code: {#code#} Sincerely, {#siteName#}', 'Published', 'AFP', NULL),
(6, 'Admin Forgot Username', 'Dear {#name#}, You have forgot User Name. Your username is {#userName#} Sincerely, {#siteName#}', 'Published', 'AFU', NULL),
(7, 'Student Login Credentials', 'Dear {#studentName#}, Your {#siteName#} account is now active. Email: {#email#} Password: {#password#} Website:{#url#} Best Regards, {#siteName#}', 'Published', 'SLC', NULL),
(8, 'User Login Credentials', 'Dear {#name#}, Your {#siteName#} account is now active. Email: {#email#} Uername: {#userName#} Password: {#password#} Website:{#url#} Best Regards, {#siteName#}', 'Published', 'ULC', NULL),
(9, 'Exam Activation', 'Dear Student, Exam Name {#examName#} Type {#type#} is active and start on {#startDate#} end on {#endDate#} Sincerely, {#siteName#}', 'Unpublished', 'EAN', NULL),
(10, 'Exam Finalized', 'Dear {#studentName#}, Name: {#examName#} Result: {#result#} Rank: {#rank#} Obtained Marks: {#obtainedMarks#} Question Attempt: {#questionAttempt#} Time Taken: {#timeTaken#} Percentage: {#percent#} % Sincerely, {#siteName#}', 'Unpublished', 'EFD', NULL),
(11, 'Exam Result', 'Dear {#studentName#}, Name: {#examName#} Result: {#result#} Obtained Marks: {#obtainedMarks#} Question Attempt: {#questionAttempt#} Time Taken: {#timeTaken#} Percentage: {#percent#} % Sincerely, {#siteName#}', 'Published', 'ERT', NULL),
(12, 'Package Purchased', 'Dear, {#studentName#} Total Amount: {#totalAmount#} Transaction Id: {#transactionId#} {#packageDetail#},  Sincerely, {#siteName#}', 'Published', 'PPD', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `sociallogins`
--

DROP TABLE IF EXISTS `sociallogins`;
CREATE TABLE IF NOT EXISTS `sociallogins` (
  `id` int(11) NOT NULL,
  `app_id` varchar(100) DEFAULT NULL,
  `app_secret` varchar(200) DEFAULT NULL,
  `callback` varchar(200) DEFAULT NULL,
  `published` varchar(3) DEFAULT 'Yes'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `stopics`
--

DROP TABLE IF EXISTS `stopics`;
CREATE TABLE IF NOT EXISTS `stopics` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `subject_id` int(11) DEFAULT NULL,
  `topic_id` int(11) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `subject_id` (`subject_id`),
  KEY `topic_id` (`topic_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `students`
--

DROP TABLE IF EXISTS `students`;
CREATE TABLE IF NOT EXISTS `students` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(100) DEFAULT NULL,
  `password` varchar(100) DEFAULT NULL,
  `name` varchar(100) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `phone` varchar(15) DEFAULT NULL,
  `guardian_phone` varchar(15) DEFAULT NULL,
  `enroll` varchar(50) DEFAULT NULL,
  `photo` varchar(100) DEFAULT NULL,
  `status` varchar(7) DEFAULT 'Pending',
  `reg_code` varchar(6) DEFAULT NULL,
  `reg_status` varchar(4) DEFAULT 'Live',
  `expiry_days` int(11) DEFAULT NULL,
  `renewal_date` date DEFAULT NULL,
  `presetcode` varchar(10) DEFAULT NULL,
  `public_key` varchar(100) DEFAULT NULL,
  `private_key` varchar(100) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  `last_login` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`),
  UNIQUE KEY `phone` (`phone`),
  UNIQUE KEY `presetcode` (`presetcode`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `student_groups`
--

DROP TABLE IF EXISTS `student_groups`;
CREATE TABLE IF NOT EXISTS `student_groups` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `student_id` int(11) NOT NULL,
  `group_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `student_id` (`student_id`,`group_id`),
  KEY `group_id` (`group_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `subjects`
--

DROP TABLE IF EXISTS `subjects`;
CREATE TABLE IF NOT EXISTS `subjects` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `subject_name` varchar(255) DEFAULT NULL,
  `section_time` int(11) DEFAULT NULL,
  `ordering` bigint(11) DEFAULT 1,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `subject_name` (`subject_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `subject_groups`
--

DROP TABLE IF EXISTS `subject_groups`;
CREATE TABLE IF NOT EXISTS `subject_groups` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `subject_id` int(11) NOT NULL,
  `group_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `subject_id` (`subject_id`),
  KEY `group_id` (`group_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `testimonials`
--

DROP TABLE IF EXISTS `testimonials`;
CREATE TABLE IF NOT EXISTS `testimonials` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `photo` varchar(100) DEFAULT NULL,
  `ordering` int(11) DEFAULT NULL,
  `rating` varchar(7) DEFAULT NULL,
  `status` varchar(7) DEFAULT 'Active',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `topics`
--

DROP TABLE IF EXISTS `topics`;
CREATE TABLE IF NOT EXISTS `topics` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `subject_id` int(11) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `subject_id` (`subject_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `ugroups`
--

DROP TABLE IF EXISTS `ugroups`;
CREATE TABLE IF NOT EXISTS `ugroups` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `ugroups`
--

INSERT INTO `ugroups` (`id`, `name`, `created`, `modified`) VALUES
(1, 'Administrator', '2012-07-05 17:16:24', '2012-07-05 17:16:24'),
(2, 'Instructor', '2014-12-12 12:03:23', '2014-12-12 12:03:23'),
(3, 'Manager', '2016-12-07 15:17:25', '2016-12-07 15:17:25');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) DEFAULT NULL,
  `password` varchar(100) DEFAULT NULL,
  `email` varchar(150) DEFAULT NULL,
  `name` varchar(40) DEFAULT NULL,
  `mobile` varchar(10) DEFAULT NULL,
  `ugroup_id` int(11) NOT NULL DEFAULT 2,
  `status` enum('Active','Suspend') DEFAULT 'Active',
  `deleted` char(1) DEFAULT NULL,
  `presetcode` varchar(10) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `presetcode` (`presetcode`),
  KEY `ugroup_id` (`ugroup_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `email`, `name`, `mobile`, `ugroup_id`, `status`, `deleted`, `presetcode`, `created`, `modified`) VALUES
(1, 'admin', 'dfb37faf99ffd691383e054541f1a3fd1966273d359d85aa419562fc26bf4427', 'root@localhost.com', 'Administrator', '0000000002', 1, 'Active', NULL, NULL, '2014-04-01 21:08:06', '2015-11-14 15:48:05');

-- --------------------------------------------------------

--
-- Table structure for table `user_groups`
--

DROP TABLE IF EXISTS `user_groups`;
CREATE TABLE IF NOT EXISTS `user_groups` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `group_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `group_id` (`group_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `wallets`
--

DROP TABLE IF EXISTS `wallets`;
CREATE TABLE IF NOT EXISTS `wallets` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `student_id` int(11) NOT NULL,
  `in_amount` decimal(18,2) DEFAULT NULL,
  `out_amount` decimal(18,2) DEFAULT NULL,
  `balance` decimal(18,2) DEFAULT NULL,
  `date` datetime DEFAULT NULL,
  `type` varchar(2) DEFAULT NULL,
  `remarks` tinytext DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `student_id` (`student_id`),
  KEY `student_id_2` (`student_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `coupons_students`
--
ALTER TABLE `coupons_students`
  ADD CONSTRAINT `coupons_students_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `coupons_students_ibfk_2` FOREIGN KEY (`coupon_id`) REFERENCES `coupons` (`id`) ON UPDATE CASCADE;

--
-- Constraints for table `exams_packages`
--
ALTER TABLE `exams_packages`
  ADD CONSTRAINT `exams_packages_ibfk_1` FOREIGN KEY (`package_id`) REFERENCES `packages` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `exams_packages_ibfk_2` FOREIGN KEY (`exam_id`) REFERENCES `exams` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `exams_subjects`
--
ALTER TABLE `exams_subjects`
  ADD CONSTRAINT `exams_subjects_ibfk_1` FOREIGN KEY (`exam_id`) REFERENCES `exams` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `exams_subjects_ibfk_2` FOREIGN KEY (`subject_id`) REFERENCES `subjects` (`id`);

--
-- Constraints for table `exam_feedbacks`
--
ALTER TABLE `exam_feedbacks`
  ADD CONSTRAINT `exam_feedbacks_ibfk_1` FOREIGN KEY (`exam_result_id`) REFERENCES `exam_results` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `exam_groups`
--
ALTER TABLE `exam_groups`
  ADD CONSTRAINT `exam_groups_ibfk_1` FOREIGN KEY (`exam_id`) REFERENCES `exams` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `exam_groups_ibfk_2` FOREIGN KEY (`group_id`) REFERENCES `groups` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `exam_maxquestions`
--
ALTER TABLE `exam_maxquestions`
  ADD CONSTRAINT `exam_maxquestions_ibfk_1` FOREIGN KEY (`exam_id`) REFERENCES `exams` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `exam_maxquestions_ibfk_2` FOREIGN KEY (`subject_id`) REFERENCES `subjects` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `exam_orders`
--
ALTER TABLE `exam_orders`
  ADD CONSTRAINT `exam_orders_ibfk_2` FOREIGN KEY (`exam_id`) REFERENCES `exams` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `exam_orders_ibfk_3` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `exam_orders_ibfk_4` FOREIGN KEY (`payment_id`) REFERENCES `payments` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `exam_preps`
--
ALTER TABLE `exam_preps`
  ADD CONSTRAINT `exam_preps_ibfk_1` FOREIGN KEY (`exam_id`) REFERENCES `exams` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `exam_preps_ibfk_2` FOREIGN KEY (`subject_id`) REFERENCES `subjects` (`id`);

--
-- Constraints for table `exam_questions`
--
ALTER TABLE `exam_questions`
  ADD CONSTRAINT `exam_questions_ibfk_1` FOREIGN KEY (`exam_id`) REFERENCES `exams` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `exam_questions_ibfk_2` FOREIGN KEY (`question_id`) REFERENCES `questions` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `exam_results`
--
ALTER TABLE `exam_results`
  ADD CONSTRAINT `exam_results_ibfk_1` FOREIGN KEY (`exam_id`) REFERENCES `exams` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `exam_results_ibfk_2` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `exam_stats`
--
ALTER TABLE `exam_stats`
  ADD CONSTRAINT `exam_stats_ibfk_1` FOREIGN KEY (`exam_id`) REFERENCES `exams` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `exam_stats_ibfk_2` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `exam_stats_ibfk_3` FOREIGN KEY (`question_id`) REFERENCES `questions` (`id`),
  ADD CONSTRAINT `exam_stats_ibfk_4` FOREIGN KEY (`exam_result_id`) REFERENCES `exam_results` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `exam_warns`
--
ALTER TABLE `exam_warns`
  ADD CONSTRAINT `exam_warns_ibfk_1` FOREIGN KEY (`exam_result_id`) REFERENCES `exam_results` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `packages_payments`
--
ALTER TABLE `packages_payments`
  ADD CONSTRAINT `packages_payments_ibfk_1` FOREIGN KEY (`payment_id`) REFERENCES `payments` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `packages_payments_ibfk_2` FOREIGN KEY (`package_id`) REFERENCES `packages` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `page_rights`
--
ALTER TABLE `page_rights`
  ADD CONSTRAINT `page_rights_ibfk_1` FOREIGN KEY (`page_id`) REFERENCES `pages` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `page_rights_ibfk_2` FOREIGN KEY (`ugroup_id`) REFERENCES `ugroups` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `payments`
--
ALTER TABLE `payments`
  ADD CONSTRAINT `payments_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `payments_ibfk_2` FOREIGN KEY (`coupon_id`) REFERENCES `coupons` (`id`) ON UPDATE CASCADE;

--
-- Constraints for table `questions`
--
ALTER TABLE `questions`
  ADD CONSTRAINT `questions_ibfk_2` FOREIGN KEY (`subject_id`) REFERENCES `subjects` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `questions_ibfk_3` FOREIGN KEY (`qtype_id`) REFERENCES `qtypes` (`id`),
  ADD CONSTRAINT `questions_ibfk_4` FOREIGN KEY (`diff_id`) REFERENCES `diffs` (`id`),
  ADD CONSTRAINT `questions_ibfk_5` FOREIGN KEY (`topic_id`) REFERENCES `topics` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `questions_ibfk_6` FOREIGN KEY (`stopic_id`) REFERENCES `stopics` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `questions_langs`
--
ALTER TABLE `questions_langs`
  ADD CONSTRAINT `questions_langs_ibfk_1` FOREIGN KEY (`question_id`) REFERENCES `questions` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `questions_langs_ibfk_2` FOREIGN KEY (`language_id`) REFERENCES `languages` (`id`) ON UPDATE CASCADE;

--
-- Constraints for table `question_groups`
--
ALTER TABLE `question_groups`
  ADD CONSTRAINT `question_groups_ibfk_1` FOREIGN KEY (`question_id`) REFERENCES `questions` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `question_groups_ibfk_2` FOREIGN KEY (`group_id`) REFERENCES `groups` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `stopics`
--
ALTER TABLE `stopics`
  ADD CONSTRAINT `stopics_ibfk_1` FOREIGN KEY (`subject_id`) REFERENCES `subjects` (`id`),
  ADD CONSTRAINT `stopics_ibfk_2` FOREIGN KEY (`topic_id`) REFERENCES `topics` (`id`);

--
-- Constraints for table `student_groups`
--
ALTER TABLE `student_groups`
  ADD CONSTRAINT `student_groups_ibfk_2` FOREIGN KEY (`group_id`) REFERENCES `groups` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `student_groups_ibfk_3` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `subject_groups`
--
ALTER TABLE `subject_groups`
  ADD CONSTRAINT `subject_groups_ibfk_1` FOREIGN KEY (`subject_id`) REFERENCES `subjects` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `subject_groups_ibfk_2` FOREIGN KEY (`group_id`) REFERENCES `groups` (`id`);

--
-- Constraints for table `topics`
--
ALTER TABLE `topics`
  ADD CONSTRAINT `topics_ibfk_1` FOREIGN KEY (`subject_id`) REFERENCES `subjects` (`id`);

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_ibfk_1` FOREIGN KEY (`ugroup_id`) REFERENCES `ugroups` (`id`);

--
-- Constraints for table `user_groups`
--
ALTER TABLE `user_groups`
  ADD CONSTRAINT `user_groups_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `user_groups_ibfk_2` FOREIGN KEY (`group_id`) REFERENCES `groups` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `wallets`
--
ALTER TABLE `wallets`
  ADD CONSTRAINT `wallets_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

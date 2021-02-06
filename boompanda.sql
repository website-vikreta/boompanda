-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 06, 2021 at 07:39 AM
-- Server version: 10.4.11-MariaDB
-- PHP Version: 7.4.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `boompanda`
--

-- --------------------------------------------------------

--
-- Table structure for table `applications`
--

CREATE TABLE `applications` (
  `id` int(11) NOT NULL,
  `email` varchar(500) NOT NULL,
  `userType` varchar(20) NOT NULL,
  `name` varchar(1000) NOT NULL,
  `UID` int(11) NOT NULL,
  `college_name` varchar(1000) NOT NULL,
  `course` varchar(100) NOT NULL,
  `year` int(11) NOT NULL,
  `state` varchar(200) NOT NULL,
  `city` varchar(200) NOT NULL,
  `taskid` int(11) NOT NULL,
  `submission` varchar(1000) NOT NULL,
  `amount_earned` int(11) NOT NULL,
  `status` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `applications`
--

INSERT INTO `applications` (`id`, `email`, `userType`, `name`, `UID`, `college_name`, `course`, `year`, `state`, `city`, `taskid`, `submission`, `amount_earned`, `status`) VALUES
(1, 'sgujarathi17699@gmail.com', 'boompanda', '', 178673, 'Sinhgad College of Engineering, Vadgaon', 'Computer Engineering', 1, 'Maharashtra', 'Pune', 7, '', 0, 'submitted'),
(2, 'sgujarathi17699@gmail.com', 'boompanda', '', 178673, 'Sinhgad College of Engineering, Vadgaon', 'Computer Engineering', 1, 'Maharashtra', 'Pune', 11, '', 0, 'submitted'),
(3, 'sgujarathi17699@gmail.com', 'boompanda', '', 178673, 'Sinhgad College of Engineering, Vadgaon', 'Computer Engineering', 1, 'Maharashtra', 'Pune', 1, '', 0, 'under review');

-- --------------------------------------------------------

--
-- Table structure for table `submissions`
--

CREATE TABLE `submissions` (
  `id` int(11) NOT NULL,
  `email` varchar(1000) NOT NULL,
  `userType` varchar(1000) NOT NULL,
  `taskid` int(11) NOT NULL,
  `name` varchar(1000) NOT NULL,
  `pemail` varchar(1000) NOT NULL,
  `mobile` varchar(100) NOT NULL,
  `state` varchar(100) NOT NULL,
  `city` varchar(100) NOT NULL,
  `college` varchar(1000) NOT NULL,
  `details` text NOT NULL,
  `proofs` varchar(1000) NOT NULL,
  `status` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `submissions`
--

INSERT INTO `submissions` (`id`, `email`, `userType`, `taskid`, `name`, `pemail`, `mobile`, `state`, `city`, `college`, `details`, `proofs`, `status`) VALUES
(1, 'sgujarathi17699@gmail.com', 'boompanda', 11, 'Shubham Gujarathi', 'sgujarathi17699@gmail.com', '8485885241', '', '', '-- Select College --', '', '', 'not approved'),
(2, 'sgujarathi17699@gmail.com', 'boompanda', 7, 'Shubham Gujarathi', 'sgujarathi17699@gmail.com', '8888888888', 'Andaman & Nicobar', ' Alipur ', 'A C Patil College of Engineering', '', '', 'not approved'),
(3, 'sgujarathi17699@gmail.com', 'boompanda', 7, 'Rudra Ghodke', 'shubham@sbgprojects.in', '8888888888', 'Andhra Pradesh', ' Adoni ', 'AAA College of Engineering & Technology', '', '', 'not approved'),
(4, 'sgujarathi17699@gmail.com', 'boompanda', 11, 'Shubham Gujarathi', 'sgujarathi17699@gmail.com', '8485885241', 'Andaman & Nicobar', ' Alipur ', 'A C Patil College of Engineering', '', '', 'not approved');

-- --------------------------------------------------------

--
-- Table structure for table `tasks`
--

CREATE TABLE `tasks` (
  `id` int(11) NOT NULL,
  `title` varchar(100) NOT NULL,
  `category` varchar(50) NOT NULL,
  `gigLogo` varchar(500) NOT NULL,
  `companyName` varchar(100) NOT NULL,
  `companyDescription` text NOT NULL,
  `startDate` varchar(100) NOT NULL,
  `endDate` varchar(100) NOT NULL,
  `boomcoins` int(11) NOT NULL,
  `complexity` varchar(20) NOT NULL,
  `sampleProofs` varchar(500) NOT NULL,
  `tutorialLink` text NOT NULL,
  `requirements` text NOT NULL,
  `completion` text NOT NULL,
  `interests` varchar(1000) NOT NULL,
  `apply` text NOT NULL,
  `noOfApplications` int(11) NOT NULL,
  `noOfSubmissions` int(11) NOT NULL,
  `status` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tasks`
--

INSERT INTO `tasks` (`id`, `title`, `category`, `gigLogo`, `companyName`, `companyDescription`, `startDate`, `endDate`, `boomcoins`, `complexity`, `sampleProofs`, `tutorialLink`, `requirements`, `completion`, `interests`, `apply`, `noOfApplications`, `noOfSubmissions`, `status`) VALUES
(1, 'Upstock Mobile App Download', 'App Download', '../media/tasks/1609556369/upstox_logo.png', 'Upstock', 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.', '2021-01-02', '2021-01-10', 20, 'Medium', '../media/tasks/1609556369/samples/', 'https://www.youtube.com/watch?v=qOBOLek2r6M', 'It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout. The point of using Lorem Ipsum is that it has a more-or-less normal distribution of letters, as opposed to using \'Content here, content here\', making it look like readable English. Many desktop publishing packages and web page editors now use Lorem Ipsum as their default model text, and a search for \'lorem ipsum\' will uncover many web sites still in their infancy. Various versions have evolved over the years, sometimes by accident, sometimes on purpose (injected humour and the like).', 'It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout. The point of using Lorem Ipsum is that it has a more-or-less normal distribution of letters, as opposed to using \'Content here, content here\', making it look like readable English. Many desktop publishing packages and web page editors now use Lorem Ipsum as their default model text, and a search for \'lorem ipsum\' will uncover many web sites still in their infancy. Various versions have evolved over the years, sometimes by accident, sometimes on purpose (injected humour and the like).', 'Latest tech & gadgets,Finance,Sales & Marketing,Social impact', 'It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout. The point of using Lorem Ipsum is that it has a more-or-less normal distribution of letters, as opposed to using \'Content here, content here\', making it look like readable English. Many desktop publishing packages and web page editors now use Lorem Ipsum as their default model text, and a search for \'lorem ipsum\' will uncover many web sites still in their infancy. Various versions have evolved over the years, sometimes by accident, sometimes on purpose (injected humour and the like).', 1, 0, 'Active'),
(2, 'Upstock Mobile App Download', 'App Download', '../media/tasks/1609556412/upstox_logo.png', 'Upstock', 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.', '2021-01-02', '2021-01-10', 20, 'Medium', '../media/tasks/1609556412/samples/', 'https://www.youtube.com/watch?v=qOBOLek2r6M', 'It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout. The point of using Lorem Ipsum is that it has a more-or-less normal distribution of letters, as opposed to using \'Content here, content here\', making it look like readable English. Many desktop publishing packages and web page editors now use Lorem Ipsum as their default model text, and a search for \'lorem ipsum\' will uncover many web sites still in their infancy. Various versions have evolved over the years, sometimes by accident, sometimes on purpose (injected humour and the like).', 'It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout. The point of using Lorem Ipsum is that it has a more-or-less normal distribution of letters, as opposed to using \'Content here, content here\', making it look like readable English. Many desktop publishing packages and web page editors now use Lorem Ipsum as their default model text, and a search for \'lorem ipsum\' will uncover many web sites still in their infancy. Various versions have evolved over the years, sometimes by accident, sometimes on purpose (injected humour and the like).', 'Latest tech & gadgets,Finance,Sales & Marketing,Social impact', 'It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout. The point of using Lorem Ipsum is that it has a more-or-less normal distribution of letters, as opposed to using \'Content here, content here\', making it look like readable English. Many desktop publishing packages and web page editors now use Lorem Ipsum as their default model text, and a search for \'lorem ipsum\' will uncover many web sites still in their infancy. Various versions have evolved over the years, sometimes by accident, sometimes on purpose (injected humour and the like).', 1, 0, 'Active'),
(3, 'Upstock Mobile', 'App Download', '../media/tasks/1609556504/upstox_logo.png', 'Upstock', 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.', '2021-01-02', '2021-01-10', 20, 'Medium', '../media/tasks/1609556504/samples/', 'https://www.youtube.com/watch?v=qOBOLek2r6M', 'It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout. The point of using Lorem Ipsum is that it has a more-or-less normal distribution of letters, as opposed to using \'Content here, content here\', making it look like readable English. Many desktop publishing packages and web page editors now use Lorem Ipsum as their default model text, and a search for \'lorem ipsum\' will uncover many web sites still in their infancy. Various versions have evolved over the years, sometimes by accident, sometimes on purpose (injected humour and the like).', 'It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout. The point of using Lorem Ipsum is that it has a more-or-less normal distribution of letters, as opposed to using \'Content here, content here\', making it look like readable English. Many desktop publishing packages and web page editors now use Lorem Ipsum as their default model text, and a search for \'lorem ipsum\' will uncover many web sites still in their infancy. Various versions have evolved over the years, sometimes by accident, sometimes on purpose (injected humour and the like).', 'Latest tech & gadgets,Finance,Sales & Marketing,Social impact', 'It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout. The point of using Lorem Ipsum is that it has a more-or-less normal distribution of letters, as opposed to using \'Content here, content here\', making it look like readable English. Many desktop publishing packages and web page editors now use Lorem Ipsum as their default model text, and a search for \'lorem ipsum\' will uncover many web sites still in their infancy. Various versions have evolved over the years, sometimes by accident, sometimes on purpose (injected humour and the like).', 0, 0, 'Active'),
(4, 'Upstock Mobile App Download', 'App Download', '../media/tasks/1609556529/upstox_logo.png', 'Upstock', 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s when an unknown printer took a galley ', '2021-01-02', '2021-01-10', 20, 'Medium', '../media/tasks/1609556529/samples/', 'https://www.youtube.com/watch?v=qOBOLek2r6M', 'It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout. The point of using Lorem Ipsum is that it has a more-or-less normal ', 'It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout. The point of using Lorem Ipsum is that it has a more-or-less ', 'Latest tech & gadgets,Finance,Sales & Marketing,Social impact', 'It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout. The point of using Lorem Ipsum is that it has a more-or-less normal distribution of letters, as opposed to using \'Content here, content here\', making it look like readable English. Many desktop publishing packages and web page editors now use Lorem Ipsum as their default model text, and a search for \'lorem ipsum\' will uncover many web sites still in their infancy. Various versions have evolved over the years, sometimes by accident, sometimes on purpose (injected humour and the like).', 0, 0, 'Active'),
(5, 'Upstock Mobile App Download', 'App Download', '../media/tasks/1609556543/upstox_logo.png', 'Upstock', 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s when an unknown printer took a galley ', '2021-01-02', '2021-01-10', 20, 'Medium', '../media/tasks/1609556543/samples/', 'https://www.youtube.com/watch?v=qOBOLek2r6M', 'It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout. The point of using Lorem Ipsum is that it has a more-or-less normal ', 'It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout. The point of using Lorem Ipsum is that it has a more-or-less ', 'Latest tech & gadgets,Finance,Sales & Marketing,Social impact', 'It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout. The point of using Lorem Ipsum is that it has a more-or-less normal distribution of letters, as opposed to using \'Content here, content here\', making it look like readable English. Many desktop publishing packages and web page editors now use Lorem Ipsum as their default model text, and a search for \'lorem ipsum\' will uncover many web sites still in their infancy. Various versions have evolved over the years, sometimes by accident, sometimes on purpose (injected humour and the like).', 0, 0, 'Active'),
(6, 'Upstock Mobile App Download', 'App Download', '../media/tasks/1609556670/upstox_logo.png', 'Upstock', 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.', '2021-01-02', '2021-01-10', 100, 'Medium', '../media/tasks/1609556670/samples/', 'https://music.amazon.in/', 'It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout. The point of using Lorem Ipsum is that it has a more-or-less normal distribution of letters, as opposed to using \'Content here, content here\', making it look like readable English. Many desktop publishing packages and web page editors now use Lorem Ipsum as their default model text, and a search for \'lorem ipsum\' will uncover many web sites still in their infancy. Various versions have evolved over the years, sometimes by accident, sometimes on purpose (injected humour and the like).', 'It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout. The point of using Lorem Ipsum is that it has a more-or-less normal distribution of letters, as opposed to using \'Content here, content here\', making it look like readable English. Many desktop publishing packages and web page editors now use Lorem Ipsum as their default model text, and a search for \'lorem ipsum\' will uncover many web sites still in their infancy. Various versions have evolved over the years, sometimes by accident, sometimes on purpose (injected humour and the like).', 'Entrepreneurship & Startups,Social impact,Climate,Self improvement', 'It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout. The point of using Lorem Ipsum is that it has a more-or-less normal distribution of letters, as opposed to using \'Content here, content here\', making it look like readable English. Many desktop publishing packages and web page editors now use Lorem Ipsum as their default model text, and a search for \'lorem ipsum\' will uncover many web sites still in their infancy. Various versions have evolved over the years, sometimes by accident, sometimes on purpose (injected humour and the like).', 0, 0, 'Active'),
(7, 'Upstock Mobile App Download', 'Instagram Promotion', '../media/tasks/1609558009/parimatch.jpg', 'aa', 'sacdfsvdgbjmhk', '2021-01-20', '2021-01-26', 11111, 'Medium', '../media/tasks/1609558009/samples/', 'https://www.youtube.com/watch?v=qOBOLek2r6M', 'ngbdfvscdxs', 'dfsdghj', 'Self improvement,Sports,Other', 'gfbhnjmk,', 1, 0, 'Active'),
(8, 'Brave browser download', 'App Download', '../media/tasks/1610688678/profile2.jpg', 'Brave', 'Brave is super speed browser\r\n-Add blocker\r\n-Saves data', '2021-01-16', '2021-01-19', 5, 'Easy', '../media/tasks/1610688678/samples/', 'https://stackoverflow.com/questions/5048849/preserve-line-breaks-from-textarea-when-writing-to-mysql', 'Brave is super speed browser\r\n-Add blocker\r\n-Saves data', 'Brave is super speed browser\r\n-Add blocker\r\n-Saves data 😳', 'Health & Fitness,Drawing & Designing', 'Brave is super speed browser\r\n-Add blocker🧘🏻‍♂️\r\n-Saves data 😳', 0, 0, 'Not Active'),
(9, 'Brave browser download', 'App Download', '../media/tasks/1610689054/profile2.jpg', 'Brave', 'sdgvhbjnk<br />\r\nadsfa<br />\r\nSFDA<br />\r\n---DA', '2021-01-15', '2021-01-19', 5, 'Medium', '../media/tasks/1610689054/samples/', 'https://stackoverflow.com/questions/5048849/preserve-line-breaks-from-textarea-when-writing-to-mysql', 'SAS', 'SASA', 'Current affairs,Sports', 'ASA', 0, 0, 'Not Active'),
(10, 'Brave browser download', 'Whatsapp Promotion', '../media/tasks/1610689273/profile2.jpg', 'Brave', 'asasa\r\n--sa\r\nsa-s', '2021-01-15', '2021-01-20', 5, 'Medium', '../media/tasks/1610689273/samples/', 'https://stackoverflow.com/questions/5048849/', 'sa', 'sas', 'Self improvement,Sports', 'sas', 0, 0, 'Not Active'),
(11, 'Brave browser download', 'App Download', '../media/tasks/1610689460/SmallLogo.png', 'Brave', 'The Brave browser is a fast, private and secure web browser for PC, Mac and mobile. Download now to enjoy a faster ad-free browsing experience that saves.\r\n-- download software\r\n-- use it for free\r\n-- prevent ads', '2021-01-15', '2021-01-17', 5, 'Easy', '../media/tasks/1610689460/samples/', 'https://brave.com/', 'As a user, access to your web activity and data is sold to the highest bidder. Internet giants grow rich, while publishers go out of business. And the entire system is rife with ad fraud.\r\n😊🥺😉😍😘😚😜😂😝😳😁😣😢\r\n😊🥺😉', 'As a user, access to your web activity and data is sold to the highest bidder. Internet giants grow rich, w\r\n😊🥺😉😍\r\n😊🥺😉', 'Novels & Writing,Finance', 'As a user, access to your web activity and data is sold to the highest bidder. Internet giants grow rich, while publishers go out of business. And the entire system is rife with ad fraud.\r\n😊🥺😉😍😘😚😜😂😝😳😁😣😢\r\n😊🥺😉', 1, 0, 'Active');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `username` varchar(100) NOT NULL,
  `profile` varchar(1000) NOT NULL,
  `password` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `profile-complete` int(11) NOT NULL,
  `userType` varchar(100) NOT NULL,
  `status` varchar(30) NOT NULL,
  `token` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `name`, `username`, `profile`, `password`, `email`, `profile-complete`, `userType`, `status`, `token`) VALUES
(1, 'Shubham Gujarathi', 'sbgprojects', '../media/profiles/1610164916.jpg', '$2y$10$AS/3Lp1b4D6bpR7mtYVl..IVsR.RH0lpgr4/tVSceW8jARFPzuhuS', 'sgujarathi17699@gmail.com', 0, 'boompanda', 'verified', '1607141304cafad9b6e1b58392bc8de1d7108ff5e8063b291baddb0ed6b3e5cde05ed2fa00'),
(3, 'Shubham Gujarathi', 'theshubham', '../media/profiles/1607153163.jpg', '$2y$10$a3CTW7eOGztjZGgvrbtMdO95ZGmfr204cCLO.LfZH2ExWIUc6EiS.', 'theshubhamgujarathi@gmail.com', 0, 'admin', 'verified', '160714142002d2e77e0969849f8969f581b584771d299c0356bc3dbbb036840d5c5d9eef37'),
(4, 'Rudra Ghodke', '', 'https://lh3.googleusercontent.com/a-/AOh14Gg0rvHhLbCjd97KuK9AUxkUDKj0izqg4k32GH1cTJI=s96-c', '', 'rudraghodke15@gmail.com', 0, 'google', 'not verified', 'ya29.a0AfH6SMAWzdU-N4ToELeCkBPoJrf8G6N6AN1yz0y8c0escze_zQd5Oorv8JCv4O3iMA9KZIyJK2F229JS6EAYAxGv1Q9Xj'),
(5, 'Adesh kolhe', '', 'https://lh3.googleusercontent.com/a-/AOh14GjVGpVjipEsYSbDrxe8wzQmfaHf3juk4uwWYdmyug=s96-c', '', 'adeshkolhe565@gmail.com', 0, 'google', 'not verified', 'ya29.a0AfH6SMAL_xrjpMZ_rLe-4kJHI60lczUUWVwVoyFo3o1A0fLYHlnvGES2JEKJM8Z8PaUhnSU5ihbt0RrCtGWGq0xD4PKlW'),
(6, 'First Admin', '', '', '$2y$10$/JojbSLS9yXEzOFJHEUe6e0Jh0MrqEv1zbYOvc/ZkmbykcF9JXlg2', 'firstadmin@gmail.com', 0, 'admin', 'verified', '160715241163bb29f54f00a34d03ff870832a309871c075530488c88efdbe13f612de0c3fd'),
(8, 'shubham Gujarathi', 'gujarathi_shubham', '../media/profiles/1609483179.jpg', '', 'sgujarathi17699@gmail.com', 0, 'google', 'not verified', 'ya29.a0AfH6SMALwopJ7KSwD55Alz3Yat-zOrcYA6bemzLCbhICGVOZyGtmRUYe-X5JOGkXe86DM6WXVZBvKJn-GskmZ9idZCtvC'),
(9, 'rutika dharangaonkar', '', 'https://lh5.googleusercontent.com/-L3xPw4TkSHI/AAAAAAAAAAI/AAAAAAAAAAA/AMZuucm0rDQe2YDlp-CW0sD4D02yMeHeKw/s96-c/photo.jpg', '', 'rutikadharangaonkar@gmail.com', 0, 'google', 'not verified', 'ya29.a0AfH6SMCRhffBPGXew0bK0vYqr9Rh5IHmgAALR_lbhs7w9_YuBhRkK89rZA2jhnFGyyvxdCUcVkqrm9DZMU-lYRzp4C4X_'),
(10, '', 'rutika1', '', '$2y$10$cUYVW62bqwVAT7e43WIFZudYlMBX6CB3dcsmG0NYQTz/XUhRn8DWK', 'rutikadharangaonkar@gmail.com', 0, 'boompanda', 'not verified', '1607323579bf5ca65b008edd53959e7ebda64d3cd56281e76dd97511631a162047109a440b'),
(11, 'pratiksha dharangaonkar', '', 'https://lh4.googleusercontent.com/-HgaXLjr8d7Q/AAAAAAAAAAI/AAAAAAAAAAA/AMZuuckxA2gBzseABbvbQAYVLxlmWGJxNA/s96-c/photo.jpg', '', 'pratikshadharangaonkar@gmail.com', 0, 'google', 'not verified', 'ya29.a0AfH6SMCnDmckX_r23bHZKdoYLLMtZp1PydCJLNYlEWSKrGXQlYahNZqneMaOJjo-3lA_9KOH31OpRgr8zY2k82hfZAG7-'),
(12, '', 'pratiksha1', '', '$2y$10$YyIf.cr9fkzRUrrE6L7ose3qT6MGL2Uk2.MIMAMTWw4/ymczrhzGC', 'pratikshadharangaonkar@gmail.com', 0, 'boompanda', 'not verified', '16073238780fbb633a54407c7611b7924af56b5bc9e13765e92a68cfe14bf3cbfd77900857'),
(13, 'Shweta Hiremath', '', 'https://lh3.googleusercontent.com/a-/AOh14GgGfwWJi13A4WmnfddKMHlM5r4k4QLXSbxOtHEA2Q=s96-c', '', 'shweta24.hiremath@gmail.com', 0, 'google', 'not verified', 'ya29.a0AfH6SMBuM-T64xEaaGjla7-8Fnk1Jys2HDU774YDFnI5tZSfgwGd_bhYc7XYBow0dUdhK6MsPrK_xVf5_6FCT1ceNnW5o'),
(14, 'Eva Dora ', 'Shweta24', '', '$2y$10$KCvezq1eGEK8w9Ui6PYDy.i0aVuSe6x3EcEPBLUbObjeQoRbIVvn6', 'shweta24.hiremath@gmail.com', 0, 'boompanda', 'not verified', '16073245819ff3e38c88f24149c245d7620b3c42223acb4dce0b98629dd1d61a4f5bc65307'),
(15, 'Sassy', '', '', '$2y$10$j9if99IItsr6sbmzlgIo7.C1Hklwl6LzWFT65Mk2ut2Yp8COWLvL2', 'shwetahiremath@gmail.com', 0, 'admin', 'verified', '1607325380c17415335e8a160f404e4f8f03a9f9c49383f6cad9a0e710e82ff08b76e8af1e'),
(16, '', 'abhikadam', '', '$2y$10$Eax87GPnViy21RtN2LKmGeZmAcVQsjzVwMwm4nmrDzbo8jdZlOHmS', 'abhishekkadam77777@gmail.com', 0, 'boompanda', 'not verified', '1607335156191d9588f7d99fb3fffd4b694a67773f418979d0ae19627a02f2faeb4af9742e'),
(17, 'Abhishekkadam', 'Soulfan', 'https://lh3.googleusercontent.com/a-/AOh14Gj6uycT0tmOfspje1rVXLn-Qi1t95U34aR2N7tpOg=s96-c', '', 'abhishekkadam77777@gmail.com', 0, 'google', 'verified', 'ya29.a0AfH6SMAzw0NthNWQGx1e_d_mIQG7LgzkNyGyeiAnylw0fexLi8GGXPQ8FmlLb2VRMcowSk2PqkJjaW6E-F-z4Mg_FZ2e0'),
(18, '', 'madjay03', '', '$2y$10$8LoJj51rA7pGUe6.zL2Wt.xLwE/bcpOQaM2DPCicZMOoDaA89rvZa', 'sujan@email.com', 0, 'boompanda', 'not verified', '1607338297385b16ccc052502aa08b30bafce92ce809db425a52c2aad6756951de670c4e2d'),
(19, 'Sujan Raktade', 'madjay', '', '258933', 'sujan@gmail.com', 0, 'boompanda', 'not verified', '1607408818409cf4e84274631cc09dd1272cb0886e119ce51962888647ea4c5f15203dd748'),
(20, 'Sujan Raktade', '', 'https://lh3.googleusercontent.com/a-/AOh14GjwWtcpZQhPh8zrpBSF3dDOuhaHua19fNvfvesSVA=s96-c', '', 'sujanraktade0146@gmail.com', 0, 'google', 'verified', 'ya29.a0AfH6SMDNiN2hMofnzR--dHu2LHA1fUis9_9JgWv-kDJqZE7q4Z9tdg28AIhg4hbdAASEq7LYe0fNnEFi1l22l3SiHBQ2n'),
(21, '', 'sujanraktade', '', '$2y$10$WL8wkMvqVAcGzZ363aBisOBUvVIbY2tjMiJSxsvZbECjLCtngz7w.', 'raktadesujan@gmail.com', 0, 'boompanda', 'verified', '1607411074f95c5c2924dea19f3418ea9d4738eba976a8c762891c4d65cc7cd8a64df70c52'),
(24, 'shubham Gujarathi', '', '', '$2y$10$V/ZyI9CVsi.A22P0lU4jSeXkpf69IFVDs1MD7xTCBxHKW/EjuscrO', 'elevagechiotspug@gmail.com', 0, 'admin', 'not verified', '1609557937b510e54beae44301c2260de4b08cdd3a75741768873d176508948d1cdbbba1d5'),
(25, '', 'shubham1', '', '$2y$10$H1ZxDE/M/CHVKeclC3Xfa./wtEmPxu4drPSTNt8bR0850nTk.mZU.', 'shubham@sbgprojects.in', 0, 'boompanda', 'verified', '161068243621c0cd9fe1e232089060e3780bb0ac17a1b30fe971887911c1946bfb677be50e'),
(26, '', 'shubham11', '', '$2y$10$lI1IhH7q.2Rzll2QCh4ZvOBuEzRP0xZeHdS/U.M6xfFmfgEnNibiy', 'shubham1@sbgprojects.in', 0, 'boompanda', 'verified', '16106824880dabf82dab569bbc64a713000f641dd01cda90859d9baa216dce8e6fbb6c7c82');

-- --------------------------------------------------------

--
-- Table structure for table `user_info`
--

CREATE TABLE `user_info` (
  `id` int(11) NOT NULL,
  `email` varchar(100) NOT NULL,
  `userType` varchar(100) NOT NULL,
  `uid` int(10) NOT NULL,
  `mobile_number` varchar(20) NOT NULL,
  `gender` varchar(50) NOT NULL,
  `dob` varchar(20) NOT NULL,
  `college` varchar(500) NOT NULL,
  `college_name` varchar(1000) NOT NULL,
  `course` varchar(500) NOT NULL,
  `year` int(11) NOT NULL,
  `state` varchar(100) NOT NULL,
  `city` varchar(100) NOT NULL,
  `language` varchar(100) NOT NULL,
  `permanant_address` varchar(1000) NOT NULL,
  `current_address` varchar(1000) NOT NULL,
  `interests` varchar(1000) NOT NULL,
  `stay` varchar(50) NOT NULL,
  `bio` varchar(1000) NOT NULL,
  `referral` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `user_info`
--

INSERT INTO `user_info` (`id`, `email`, `userType`, `uid`, `mobile_number`, `gender`, `dob`, `college`, `college_name`, `course`, `year`, `state`, `city`, `language`, `permanant_address`, `current_address`, `interests`, `stay`, `bio`, `referral`) VALUES
(1, 'sgujarathi17699@gmail.com', 'boompanda', 178673, '8485885241', 'male', '1999-07-17', '2169+411041', 'Sinhgad College of Engineering, Vadgaon', 'Computer Engineering', 1, 'Maharashtra', 'Pune', '', 'Sunny palace road, khamgaon - 444303', 'C-02 Pune', 'Latest tech & gadgets,Music & Singing,Theatre & Acting,Health & Fitness,Movies & TV series', 'Outside college campus', '</web designer>', 'NONEVALA'),
(3, 'theshubhamgujarathi@gmail.com', 'admin', 414823, '8485885241', 'Male', '1999-06-17', '', '', '', 0, 'Maharashtra', 'Pune', 'English', '', '', '', '', '', ''),
(4, 'rudraghodke15@gmail.com', 'google', 838096, '', 'undefined', '', '2169+411041', '', 'Information Technology', 0, 'Maharashtra', 'Pune', '', '', '', 'Latest tech & gadgets,Music & Singing,Theatre & Acting,Health & Fitness,Movies & TV series', 'College hostel', '', ''),
(5, 'adeshkolhe565@gmail.com', 'google', 946013, '', '', '', '', '', '', 0, '', '', '', '', '', '', '', '', ''),
(6, 'firstadmin@gmail.com', 'admin', 215778, '', 'Male', '', '', '', '', 0, 'Maharashtra', 'Pune', 'Marathi', '', '', '', '', '', ''),
(8, 'sgujarathi17699@gmail.com', 'google', 240851, '8485885241', 'male', '1999-06-17', '2169+411041', 'Sinhgad College of Engineering, Vadgaon', 'Computer Engineering', 4, 'Maharashtra', 'Pune', '', '', '', 'Latest tech & gadgets,Music & Singing,Theatre & Acting,Health & Fitness,Novels & Writing', 'Outside college campus', '', ''),
(9, 'rutikadharangaonkar@gmail.com', 'google', 556921, '', '', '', '', '', '', 0, '', '', '', '', '', '', '', '', ''),
(10, 'rutikadharangaonkar@gmail.com', 'boompanda', 162053, '', '', '', '', '', '', 0, '', '', '', '', '', '', '', '', ''),
(11, 'pratikshadharangaonkar@gmail.com', 'google', 639501, '', '', '', '', '', '', 0, '', '', '', '', '', '', '', '', ''),
(12, 'pratikshadharangaonkar@gmail.com', 'boompanda', 111346, '', '', '', '', '', '', 0, '', '', '', '', '', '', '', '', ''),
(13, 'shweta24.hiremath@gmail.com', 'google', 138228, '', '', '', '', '', '', 0, '', '', '', '', '', '', '', '', ''),
(14, 'shweta24.hiremath@gmail.com', 'boompanda', 657104, '2257136940', 'female', '2000-03-23', '136+303002', '', 'Animation and Multimedia Engineering', 0, 'Rajasthan', 'Pali', '', 'xyz', 'xyz', '', 'College hostel', '', ''),
(15, 'shwetahiremath@gmail.com', 'admin', 870837, '', '', '', '', '', '', 0, 'Maharashtra', 'Pune', 'Deutsch', '', '', '', '', '', ''),
(16, 'abhishekkadam77777@gmail.com', 'boompanda', 382872, '', '', '', '', '', '', 0, '', '', '', '', '', '', '', '', ''),
(17, 'abhishekkadam77777@gmail.com', 'google', 301852, '8888982636', 'male', '2000-02-23', '2169+411041', '', 'Information Technology', 0, 'Maharashtra', 'Pune', '', 'Vadgaon.bk,pune', 'Vadgaon.bk,pune', 'Latest tech & gadgets,Music & Singing,Health & Fitness,Movies & TV series,Self improvement', 'Outside college campus', 'A simple guy.', ''),
(18, 'sujan@email.com', 'boompanda', 360643, '', '', '', '', '', '', 0, '', '', '', '', '', '', '', '', ''),
(19, 'sujan@gmail.com', 'boompanda', 897659, '7218470146', 'male', '2000-11-03', '2169+411041', '', 'Computer Engineering', 0, 'Maharashtra', 'Gadhinglaj', '', 'AT-Post Nanar\nTal-Rajapur\nDist-Ratnagiri', 'AT-Post Nanar\nTal-Rajapur\nDist-Ratnagiri', 'Latest tech & gadgets,Finance,Current affairs,Sports,Other', 'Outside college campus', '', ''),
(20, 'sujanraktade0146@gmail.com', 'google', 406367, '', '', '', '', '', '', 0, '', '', '', '', '', '', '', '', ''),
(21, 'raktadesujan@gmail.com', 'boompanda', 338859, '', '', '', '', '', '', 0, '', '', '', '', '', '', '', '', ''),
(24, 'elevagechiotspug@gmail.com', 'admin', 475195, '', '', '', '', '', '', 0, 'Haryana', 'Ellenabad', 'enf', '', '', '', '', '', ''),
(25, 'shubham@sbgprojects.in', 'boompanda', 359396, '', '', '', '', '', '', 0, '', '', '', '', '', '', '', '', ''),
(26, 'shubham1@sbgprojects.in', 'boompanda', 371397, '', '', '', '', '', '', 0, '', '', '', '', '', '', '', '', '');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `applications`
--
ALTER TABLE `applications`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `submissions`
--
ALTER TABLE `submissions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tasks`
--
ALTER TABLE `tasks`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user_info`
--
ALTER TABLE `user_info`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `applications`
--
ALTER TABLE `applications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `submissions`
--
ALTER TABLE `submissions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `tasks`
--
ALTER TABLE `tasks`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `user_info`
--
ALTER TABLE `user_info`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

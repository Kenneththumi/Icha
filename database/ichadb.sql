-- phpMyAdmin SQL Dump
-- version 4.3.8
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Oct 19, 2015 at 04:20 AM
-- Server version: 5.5.42-37.1
-- PHP Version: 5.4.23

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `nerojobs_icha`
--

-- --------------------------------------------------------

--
-- Table structure for table `accesslevels`
--

CREATE TABLE IF NOT EXISTS `accesslevels` (
  `accessid` int(11) NOT NULL,
  `displayname` text NOT NULL,
  `accesslevel` text NOT NULL,
  `deletionallowed` text NOT NULL
) ENGINE=MyISAM AUTO_INCREMENT=17 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `accesslevels`
--

INSERT INTO `accesslevels` (`accessid`, `displayname`, `accesslevel`, `deletionallowed`) VALUES
(7, 'SuperSystemAdministrator', 'superadmin', 'yes'),
(8, 'Admin', 'adm', 'yes'),
(9, 'Frontend', 'ftd', 'no'),
(14, 'Student', 'std', 'yes'),
(15, 'Instructor', 'ins', 'yes'),
(16, 'Subscriber', 'sub', 'no');

-- --------------------------------------------------------

--
-- Table structure for table `articles`
--

CREATE TABLE IF NOT EXISTS `articles` (
  `articleid` int(11) NOT NULL,
  `title` text NOT NULL,
  `alias` varchar(300) NOT NULL,
  `body` longtext NOT NULL,
  `categoryid` int(11) NOT NULL,
  `publishdate` int(11) NOT NULL,
  `hits` int(11) NOT NULL,
  `lead` text NOT NULL,
  `atype` varchar(20) NOT NULL,
  `parentid` int(11) NOT NULL
) ENGINE=MyISAM AUTO_INCREMENT=1128 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `articles`
--

INSERT INTO `articles` (`articleid`, `title`, `alias`, `body`, `categoryid`, `publishdate`, `hits`, `lead`, `atype`, `parentid`) VALUES
(8, 'About ICHA', 'about-icha', '<!DOCTYPE html>\r\n<html>\r\n<head>\r\n</head>\r\n<body>\r\n<p style="text-align: justify;"><img src="http://localhost/icha/media/images/university-about.jpg" alt="" width="968" height="645" /></p>\r\n<p style="text-align: justify;">The International Center for Humanitarian Affairs) strives to create an appropriate and effective knowledge management &nbsp;framework that synthesizes multiple information technologies to collect, analyze, and, manage information and knowledge for supporting decision making in humanitarian action, disaster relief and improving community resilience.</p>\r\n<p style="text-align: justify;">The framework so developed is intended to help identify, specify and quantify information needs, track status of disaster scenarios and provide policy makers and practitioners with efficient and sustainable recommendations based on past experience and research based evidence.</p>\r\n<p style="text-align: justify;">ICHA is a knowledge hub which focuses on generating data and information through action based research that is relevant to communities dealing with situations that call for humanitarian, resilience building and development action.&nbsp;</p>\r\n<h4>Mission &amp; Goal</h4>\r\n<p style="text-align: justify;">To contribute extensive knowledge that will help alleviate human suffering through building safe, resilient and sustainable communities.</p>\r\n<p style="text-align: justify;">Through extensive and high quality research training and publications coupled with strategic partnerships, ICHA&rsquo;s short term goal is to be a Knowledge Node not only for the KRCS, but a national and regional hub for knowledge on humanitarian affairs, improved community resilience and developmental issues.</p>\r\n<h4>How University got its start &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;</h4>\r\n<p style="text-align: justify;">Over the years, the KRCS (Kenya Red Cross Society) has been able to develop a rich knowledge database on humanitarian, development action and systems sustainability. The KRCS appreciates that it is only through history, analysis of trend, knowledge generation and management that we can accumulate foresight to effectively plan and prepare for humanitarian, disaster relief, mitigation and lifting millions out of livelihood vulnerabilities.</p>\r\n<p style="text-align: justify;">In the past few decades, actors in humanitarian relief have since increased and diversified to include Government, UN agencies, Development Partners,International and National Non-Governmental Organizations, Civil Society Organizations and most recently the private sector and even private individuals. Indeed, the advent of proliferation of actors in the humanitarian relief sector is an extremely positive development as it strengthens the course towards alleviating human suffering and vulnerability.</p>\r\n<p style="text-align: justify;">However, such growth and diversity is not without challenges. The major challenge has been and continues to be the lack of a harmonized, synchronized and well-coordinated knowledge base; this in turn constrains effective utilization of information especially during humanitarian, disaster reliefoperations, and resilient building interventions. It is against this backdrop, that having identified the need to ensure efficient and effective knowledge and information management, that the KRCS established the International Center for Humanitarian Affairs (( hereinafter referred to as ICHA).</p>\r\n<h4>Quick facts:</h4>\r\n<ul>\r\n<li>Founded in 2013</li>\r\n<li>Located in Nairobi Kenya</li>\r\n</ul>\r\n</body>\r\n</html>', 0, 1441043401, 1345, '', '', 0),
(1126, 'Our History', 'our-history', '<!DOCTYPE html>\r\n<html>\r\n<head>\r\n</head>\r\n<body>\r\n<p style="text-align: justify;">Over the years, the KRCS (Kenya Red Cross Society) has been able to develop a rich knowledge database on humanitarian, development action and systems sustainability. The KRCS appreciates that it is only through history, analysis of trend, knowledge generation and management that we can accumulate foresight to effectively plan and prepare for humanitarian, disaster relief, mitigation and lifting millions out of livelihood vulnerabilities.<br /><br />In the past few decades, actors in humanitarian relief have since increased and diversified to include Government, UN agencies, Development Partners,International and National Non-Governmental Organizations, Civil Society Organizations and most recently the private sector and even private individuals. Indeed, the advent of proliferation of actors in the humanitarian relief sector is an extremely positive development as it strengthens the course towards alleviating human suffering and vulnerability.<br /><br />However, such growth and diversity is not without challenges. The major challenge has been and continues to be the lack of a harmonized, synchronized and well-coordinated knowledge base; this in turn constrains effective utilization of information especially during humanitarian, disaster reliefoperations, and resilient building interventions. It is against this backdrop, that having identified the need to ensure efficient and effective knowledge and information management, that the KRCS established the International Center for Humanitarian Affairs (( hereinafter referred to as ICHA)</p>\r\n<h4>Key Milestones</h4>\r\n<table>\r\n<tbody>\r\n<tr>\r\n<td><strong> 2000</strong></td>\r\n<td>&nbsp;the idea was born</td>\r\n</tr>\r\n<tr>\r\n<td><strong> 2010</strong></td>\r\n<td>The short medical training was launched</td>\r\n</tr>\r\n<tr>\r\n<td><strong>2013</strong></td>\r\n<td>We opened our doors.</td>\r\n</tr>\r\n</tbody>\r\n</table>\r\n<p>&nbsp;</p>\r\n</body>\r\n</html>', 0, 1441047711, 58, '', '', 0),
(1117, 'Contacts', 'contacts', '<p><strong>International Centre for Humanitarian Affairs</strong>, </p>\n<p>PO Box 40712-00100 Nairobi, Kenya </p>\n<p> Phone: +2540722-206958</p>', 0, 1391969800, 0, '', '', 0),
(1111, 'Welcome to Icha<br/> The Distinguished Center of Excellence', 'welcome-to-icha', '<p>ICHA is a knowledge hub which focuses on generating data and information through action based research that is relevant to communities dealing with situations that call for humanitarian, resilience building and development action. The International Center for Humanitarian Affairs) strives to create an appropriate and effective knowledge management  framework that synthesizes multiple information technologies to collect, analyze, and, manage information and knowledge for supporting decision making in humanitarian action, disaster relief and improving community resilience.</p>', 0, 1390566654, 513, 'yes', '', 0),
(1112, 'The Prestigious EMT School', 'the-prestigious-emt', '<p>The framework so developed is intended to help identify, specify and quantify information needs, track status of disaster scenarios and provide policy makers and practitioners with efficient and sustainable recommendations based on past experience and research based evidence.</p>', 0, 1391156011, 449, '', '', 0),
(1113, 'We Bring Life To People', 'we-bring-life', '<p>Through extensive and high quality research training and publications coupled with strategic partnerships, ICHA’s short term goal is to be a Knowledge Node not only for the KRCS, but a national and regional hub for knowledge on humanitarian affairs, improved community resilience and developmental issues.</p>', 0, 1384609022, 529, 'yes', '', 0),
(1115, 'Focus Areas', 'focus-areas', '<ul class="focusarea">\r\n<li>Constitutional Development and  Law Reform, </li>\r\n<li>Devolution, </li>\r\n<li>Governance and Rule of Law, </li>\r\n<li>Access to Justice and Human Rights, </li>\r\n<li>Human Security and Development, and Public Financial Management</li>\r\n</ul>', 0, 1389353765, 0, '', '', 0),
(1118, 'Admissions', 'admissions', '<p><img src="media/images/admissions.jpg" width="310" height="200" /></p><p>Some of the Courses provided include:  Short Courses, Leadership ...</p>', 0, 0, 0, '', '', 0),
(1119, 'Training & Education', 'training-education', '<p><img src="media/images/trainingneducation.jpg" width="310" height="200" /></p><p>ICHA also offers courses which are designed to provide capacity in   addressing various emergencies...</p>', 1, 0, 0, '', '', 0),
(1120, 'Research', 'research', '<p><img src="media/images/research.jpg" width="318" height="200" /></p><p>Through extensive and high quality research training and publications   coupled with strategic partnerships, ICHA</p>', 1, 0, 0, '', '', 0),
(1121, 'Emergency Medical Services', 'emergency-medical-services', '<!DOCTYPE html>\r\n<html>\r\n<head>\r\n</head>\r\n<body>\r\n<p>The KRCS appreciates that it is only through history, analysis of trend and knowledge generation that we can accumulate.</p>\r\n<p><strong>Elizabeth Kibe,</strong> Nairobi</p>\r\n</body>\r\n</html>', 2, 1443692887, 0, '', '', 0),
(1122, 'Kenya Red Cross Society', 'kenya-red-cross', '<!DOCTYPE html>\r\n<html>\r\n<head>\r\n</head>\r\n<body>\r\n<p>"The KRCS appreciates that it is only through history, analysis of trend, knowledge generation and management that we can accumulate foresight to effectively plan and prepare for humanitarian, disaster relief, mitigation and lifting millions out of livelihood vulnerabilities."</p>\r\n<p><strong>John Paul,</strong> Nairobi</p>\r\n</body>\r\n</html>', 2, 1442997202, 0, '', '', 0),
(1123, 'Courses Offered', 'courses-offered', '<!DOCTYPE html>\r\n<html>\r\n<head>\r\n</head>\r\n<body>\r\n<p><img src="media/images/coursesoffered.png" alt="" width="390" height="212" /></p>\r\n<ul>\r\n<li><a href="?content=com_courses&amp;folder=same&amp;file=showdetails&amp;cid=95">Project Management</a></li>\r\n<li><a href="?content=com_courses&amp;folder=same&amp;file=showdetails&amp;cid=96">Monitoring and evaluation</a></li>\r\n<li><a href="?content=com_courses&amp;folder=same&amp;file=showdetails&amp;cid=97">Conflict and Peace Building</a></li>\r\n<li><a href="?content=com_courses&amp;folder=same&amp;file=showdetails&amp;cid=108">Effective Humanitarian leadership</a></li>\r\n<li><a href="?content=com_courses&amp;folder=same&amp;file=showdetails&amp;cid=110">Emergency and disaster Management</a></li>\r\n</ul>\r\n<ul>\r\n<li><a href="?content=com_courses&amp;folder=same&amp;file=showdetails&amp;cid=112">Nursing</a></li>\r\n<li><a href="?content=com_courses&amp;folder=same&amp;file=showdetails&amp;cid=113">Paramedicine</a></li>\r\n<li><a href="?content=com_courses&amp;folder=same&amp;file=showdetails&amp;cid=98">Basic First Aid Course</a></li>\r\n<li>Basic Fire Safety Course</li>\r\n<li>Advance Trauma Life Support</li>\r\n</ul>\r\n</body>\r\n</html>', 3, 1443177054, 0, '', '', 0),
(1124, 'About Icha', 'about-icha', '<p><img src="media/images/abouticha.png" width="393" height="213" />\r\n</p>\r\n<p>The International Center for Humanitarian Affairs) strives to create an appropriate and effective\r\n  knowledge management  framework that synthesizes multiple information technologies to collect,\r\n  analyze, and, manage information and knowledge for supporting decision making in humanitarian\r\n  action, disaster relief and improving community resilience.<br />\r\nThe framework so developed is intended to help identify, specify and quantify information needs</p>', 3, 0, 0, '', '', 0),
(1125, 'Policy & Advocacy', 'policy-advocacy', '<p><img src="media/images/policynadvocacy.jpg" width="318" height="200" /></p><p>ICHA is a knowledge hub which focuses on generating data and information   through action based...</p>', 1, 0, 0, '', '', 0);

-- --------------------------------------------------------

--
-- Table structure for table `bulletins`
--

CREATE TABLE IF NOT EXISTS `bulletins` (
  `bulletinid` int(11) NOT NULL,
  `title` text NOT NULL,
  `body` longtext NOT NULL,
  `category` varchar(100) NOT NULL,
  `publishdate` int(11) NOT NULL,
  `enabled` text NOT NULL
) ENGINE=MyISAM AUTO_INCREMENT=19 DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `carthistory`
--

CREATE TABLE IF NOT EXISTS `carthistory` (
  `historyid` int(11) NOT NULL,
  `subscriberid` int(11) NOT NULL,
  `productsbought` text NOT NULL,
  `cartvalue` varchar(100) NOT NULL,
  `datetime` int(11) NOT NULL
) ENGINE=MyISAM AUTO_INCREMENT=56 DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE IF NOT EXISTS `categories` (
  `categoryid` int(11) NOT NULL,
  `name` text NOT NULL,
  `description` text NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`categoryid`, `name`, `description`) VALUES
(1, 'Introduction', '<!DOCTYPE html>\r\n<html>\r\n<head>\r\n</head>\r\n<body>\r\n<p>These are the articles which will serve as an Introduction to the homepage</p>\r\n</body>\r\n</html>'),
(2, 'Testimonials', '<!DOCTYPE html>\r\n<html>\r\n<head>\r\n</head>\r\n<body>\r\n<p>These are individual accounts of their experiences with ICHA</p>\r\n</body>\r\n</html>'),
(3, 'About ICHA', '<!DOCTYPE html>\r\n<html>\r\n<head>\r\n</head>\r\n<body>\r\n<p>These are Articles about the ICHA institution</p>\r\n</body>\r\n</html>'),
(4, 'Short Courses', '<!DOCTYPE html>\r\n<html>\r\n<head>\r\n</head>\r\n<body>\r\n<p>ICHA also offers short courses which are designed to provide capacity in addressing &nbsp; various emergencies, disasters and emerging leadership challenges in Kenya and regional communities. These courses range between 1 &ndash; 10 days sessions</p>\r\n</body>\r\n</html>'),
(5, 'Leadership and Management', '<!DOCTYPE html>\r\n<html>\r\n<head>\r\n</head>\r\n<body>\r\n<p>The International Centre for Humanitarian Affairs (ICHA) has developed Humanitarian Leadership courses for Red Cross Society leaders in Africa. This courses are &nbsp;in response to a leadership gap that many in the Red Cross Movement have recognised.&nbsp;</p>\r\n<p>These courses range between 10 &ndash; 15 days sessions</p>\r\n</body>\r\n</html>'),
(6, 'Emergency Management', '<!DOCTYPE html>\r\n<html>\r\n<head>\r\n</head>\r\n<body>\r\n<p>The Course targets representatives from relevant Government ministries/departments, Emergency Service Providers, Humanitarian/relief workers, disaster managers, and the general public.</p>\r\n</body>\r\n</html>'),
(7, 'Paramedicine and Nursing courses', '<!DOCTYPE html>\r\n<html>\r\n<head>\r\n</head>\r\n<body>\r\n<p>The Course targets representatives from relevant Government ministries/departments, Emergency Service Providers.</p>\r\n</body>\r\n</html>'),
(8, 'News', '<!DOCTYPE html>\r\n<html>\r\n<head>\r\n</head>\r\n<body>\r\n<p>These are the articles which will be the websites news items</p>\r\n</body>\r\n</html>');

-- --------------------------------------------------------

--
-- Table structure for table `contributors`
--

CREATE TABLE IF NOT EXISTS `contributors` (
  `contributorid` int(11) NOT NULL,
  `name` varchar(300) NOT NULL,
  `emailaddress` text NOT NULL,
  `telephone` varchar(200) NOT NULL,
  `contactinfo` text NOT NULL,
  `category` varchar(100) NOT NULL,
  `enabled` varchar(11) NOT NULL,
  `registrationdate` int(11) NOT NULL,
  `listorder` int(11) NOT NULL,
  `designation` varchar(200) NOT NULL,
  `foldername` varchar(100) DEFAULT NULL
) ENGINE=MyISAM AUTO_INCREMENT=14 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `contributors`
--

INSERT INTO `contributors` (`contributorid`, `name`, `emailaddress`, `telephone`, `contactinfo`, `category`, `enabled`, `registrationdate`, `listorder`, `designation`, `foldername`) VALUES
(2, 'Dr James Kisia', 'info@icha.net', '', '<!DOCTYPE html>\r\n<html>\r\n<head>\r\n</head>\r\n<body>\r\n<p>&nbsp;</p>\r\n<div class="itemFullText">\r\n<p style="text-align: justify;">Dr. James Kisia, MD, MSc, MA, is the Executive Director ICHA and Deputy Secretary General Kenya Red Cross. He has over 15 years in Health care management and Humanitarian work. &nbsp;His practice has included program formulation, implementation and evaluation as well as research and advocacy. He teaches post graduate Obstetrics and Gynaecology as well as Paediatric doctors in an innovative course on implementation science hosted by the University of Nairobi''s Institute of Tropical and Infectious Diseases. &nbsp;He is also a frequent guest speaker at the Lancaster University Management School Executive MBA course.&nbsp;</p>\r\n<p style="text-align: justify;">Dr Kisia''s interest in research has ranged from public health to health emergencies and creating resilient communities. &nbsp;He is biased towards community health, community livelihoods and creation of resilient rural communities.</p>\r\n<p style="text-align: justify;">Dr.Kisia earned his medical degree from Spartan Health Sciences University, School of Medicine. He also holds a PGD and MSC from London School of Hygiene and Tropical Medicine. &nbsp;In the development of his career as a senior manager Dr. Kisia also earned a Master of Arts in Practicing Management from Lancaster University Management School where he graduated with Distinction. &nbsp;He has also studied Health Innovation and Leadership at INSEAD at Fontainebleau, France. &nbsp;</p>\r\n<p style="text-align: justify;">Before joining ICHA, James worked for Kenya Red Cross where he headed the Health Department for 3 years before taking up the Deputy &nbsp;Secretary General position in which he was Head of Programs for the organization. &nbsp;Previous the KRC, James worked as a General Manager and Head of Medical at Integri Health, an Health Management Organization. Prior to this he worked with the Kenya Ministry of Health as a Medical Officer in some of the most marginalized and underserved parts of the country. &nbsp;</p>\r\n<p style="text-align: justify;">Dr.Kisia has served in several Advisory Committee, nationally and globally including the IFRC Health Commission. &nbsp;He is a member of the global thought leadership group; Advancing Health Decision Making, &nbsp;hosted by the European Academy of Business Institutes. He is the focal point for Maastrich University School of Public Health Post Graduate research activities in Kenya&nbsp;</p>\r\n</div>\r\n<p>&nbsp;</p>\r\n</body>\r\n</html>', 'Instructor', 'Yes', 1442562675, 1, 'Executive Director', 'drjamesk'),
(10, 'Ahmed Idris', 'info@icha.net', '', '<!DOCTYPE html>\r\n<html>\r\n<head>\r\n</head>\r\n<body>\r\n<p style="text-align: justify;">Ahmed is the Head of Policy at the International Centre of Humanitarian Affairs at the Kenya Red Cross. He holds a B.A (Political Science), Bachelor of Laws, and LL.M in International Law from University of Nairobi and a Master of Arts in Law and Diplomacy from the Fletcher School of Law and Diplomacy in the US. Ahmed has been a Fulbright Fellow and a Fletcher Board of Overseers Scholar at the Fletcher School. He is currently a Ph.D Candidate specializing in International Law. Ahmed is a member of the International Bar Association (IBA), The American Society of International Law (ASIL), International Law Association (ILA) and The International Association of Professionals in Humanitarian Assistance and Protection (PHAP). He has professional interests in Public International Law, International Humanitarian Law, Ethics in International Relations and Negotiations.&nbsp;</p>\r\n<p style="text-align: justify;">Ahmed has been involved in peace negotiations between various communities in Northern Kenya which has resulted in atleast two peace pacts. He has contributed to the development of the International Humanitarian Law as an Instructor with the Kenya Police and Department of Defense. He also sat in the National Committee on Implementation of International Humanitarian Law.&nbsp;</p>\r\n<p style="text-align: justify;">He has an interest in teaching international law and politics where he has taught various courses in International Law in Kenya and outside including Tufts University in the US. He is currently the Governance Support Manager at Kenya Red Cross. He is also a trainer with the Department of Defence.&nbsp;</p>\r\n<p style="text-align: justify;">Ahmed has published articles and authored chapters in two books. He is also an External Editor of the Resilience: Interdisciplinary Perspectives on Science and Humanitarianism Journal. He is currently writing a book under the working title &ldquo;Whose Citizen? A century State Impunity in North Eastern Kenya&rdquo;</p>\r\n</body>\r\n</html>', 'Instructor', 'Yes', 1442562868, 2, ' Head of Policy at the International Centre of Humanitarian Affairs', 'ahmedidr'),
(11, 'Agnes Enid Koome', 'info@icha.net', '', '<!DOCTYPE html>\r\n<html>\r\n<head>\r\n</head>\r\n<body>\r\n<div class="itemFullText">\r\n<p style="text-align: justify;">Agnes Enid Koome is a Disaster Management Specialist. She has been working for 14 years with the Kenya Red Cross in various programmes namely First Aid, Disease Prevention and Control, Disaster Preparedness and Training. She holds a Bachelor&rsquo;s degree in Education, a Diploma in Marketing Management, International Certificate in Practicing Management and a Masters in International Executive Management. She is also finalizing her thesis for the Master&rsquo;s Degree in Disaster Management and Humanitarian Assistance.&nbsp;</p>\r\n<p style="text-align: justify;">She has held various positions within the Kenya Red Cross Society as the Training Manager, Disaster Preparedness Manager, First Aid Manager and the Disease Prevention and Control focal point.She has organised several courses both in and out of the country for the Kenya Red Cross, ICRC, IFRC/Norwegian Red Cross,Tanzania Red Cross, Somalia Red Crescent, IRC and Djibouti Red Crescent Societies, The first National Disaster Response Team(NDRT) by KRCS, Vulnerability and Capacity Assessment(VCA) Trainer of Trainers Course for staff and volunteers, and various Disaster Management courses targeting key partners and stakeholders.</p>\r\n<p style="text-align: justify;">She has undertaken several courses in and out of the country on organising Emergency Medical Services(Israel), Public Health in Complex emergencies(Makerere University, Uganda), Disaster Preparedness and Response for Emergency Responders(Kenya), Leadership Course in Regional Disaster Response and Trauma Systems Management by the Defence Institute for Medical Operation (DIMO), USA and Trainer of Trainers course on Coastal Mitigation, Result Based Monitoring and Evaluation(MTDC, Arusha),Reproductive Health(UNFPA), Grants Management(KCA University). She has travelled widely for work and training to the UK,Canada, Japan, India, China, Rwanda, South Africa, Brazil, Tanzania and Zanzibar, Zambia, Somaliland, Uganda, Italy, Switzerland and Germany.</p>\r\n</div>\r\n</body>\r\n</html>', 'Instructor', 'Yes', 1442562940, 3, 'Disaster Management Specialist', 'agneseni'),
(12, 'Dr.Uzma Alam', 'info@icha.net', '', '<!DOCTYPE html>\r\n<html>\r\n<head>\r\n</head>\r\n<body>\r\n<div class="itemFullText">\r\n<p style="text-align: justify;">Dr. Uzma Alam holds a PhD and a Masters in Public Health from Yale University. She is passionate about improving public health in developing countries. Being a native of Kenya her interest in public health stemmed early and out of first hand experiences. Her career interest revolves around incorporating her research background with public health policy and practice. She has national and international experience serving with academic, non-profit and governmental entities.</p>\r\n<p style="text-align: justify;">For her work she has received the American Society of Hygiene &amp; Tropical Medicine Young Investigator honorary award. She has also been elected into the Sigma Xi society. Additionally, she is a reviewer for both, the Yale Journal of Biology and Medicine (YJBM) and The Yale Journal of Health Policy, Law and Ethics. (YJHPLE) Out side of academia, Alam has served as the Vice Chair for the National Postdoctoral Association and on the National Association for Women in Science (AWIS), Connecticut Executive Board.</p>\r\n</div>\r\n</body>\r\n</html>', 'Instructor', 'Yes', 1442553216, 4, '', 'dr.uzmaa'),
(13, 'John Nduri', 'info@icha.net', '', '<!DOCTYPE html>\r\n<html>\r\n<head>\r\n</head>\r\n<body>\r\n<div class="itemFullText">\r\n<p style="text-align: justify;">John Nduri an expert in setting up and managing research, information and knowledge management services. He has a strong understanding of how to communicate research and knowledge audit findings in ways that are accessible to teams, decision-makers and stakeholders.</p>\r\n<p style="text-align: justify;">He worked for USAID''s Africa Lead II Program as The Knowledge Management &amp; Communications Specialist in charge of 12 countries in East and Southern Africa between from January 2013 to January 2015. He has also worked for NIRAS Finland Oy as a Short-term Knowledge Management Expert within the Millennium Development Goals Program Implementation Unit and its main stakeholders in Kenya. From August 2011 to-date, he serves as a Volunteer Rapporteur and Member; Technical Committee at Knowledge Management Africa - Kenya Chapter.</p>\r\n<p style="text-align: justify;">His specialties include Knowledge Management and Communications, Research Survey Design, Data Analysis and Reporting, Proof-reading and Editing, Complexity-Aware Monitoring and Evaluation, New / Social Media Strategy and Execution.</p>\r\n<p style="text-align: justify;">John holds a Bachelor of Arts degree from Moi University and is currently pursuing MSc. in Information and Knowledge Management at Jomo Kenyatta University of Agriculture and Technology. He is an Accredited Knowledge Management Consultant trained by Knowledge Associates Cambridge, UK and has certification in Statistics Made Simple from The University of Reading, UK.</p>\r\n</div>\r\n</body>\r\n</html>', 'Instructor', 'Yes', 1441210762, 5, 'Research', 'johnndur');

-- --------------------------------------------------------

--
-- Table structure for table `courses`
--

CREATE TABLE IF NOT EXISTS `courses` (
  `courseid` int(11) NOT NULL,
  `coursename` text NOT NULL,
  `description` text NOT NULL,
  `enabled` text NOT NULL,
  `parentid` int(11) NOT NULL,
  `categoryid` int(11) NOT NULL,
  `publishdate` int(11) DEFAULT NULL,
  `startdate` int(11) NOT NULL,
  `enddate` int(11) NOT NULL,
  `contributorid` int(11) NOT NULL,
  `brochure` varchar(400) NOT NULL
) ENGINE=MyISAM AUTO_INCREMENT=117 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `courses`
--

INSERT INTO `courses` (`courseid`, `coursename`, `description`, `enabled`, `parentid`, `categoryid`, `publishdate`, `startdate`, `enddate`, `contributorid`, `brochure`) VALUES
(95, 'Project Mangement Course ', '<!DOCTYPE html>\r\n<html>\r\n<head>\r\n</head>\r\n<body>\r\n<h4>What to Expect.</h4>\r\n<p><em>Project management is one of the most highly sought after professionals in the corporate world and public sector. The course is provides knowledge and skills required to deliver projects on time and on budget.</em></p>\r\n<h4>Personal benefits</h4>\r\n<p>&bull;The program helps you to develop critical project management skills you need to effectively manage programs or projects in a variety complex environments</p>\r\n<p>&bull;Learn how to deliver a projects on time and on budget&nbsp; </p>\r\n<h4>&nbsp;Organizational benefits</h4>\r\n<p>&bull;Able to plan, communicate and ensure quality control</p>\r\n<p>&bull;Able to ensure projects are completed in time and on budget</p>\r\n</body>\r\n</html>', 'Yes', 0, 4, 1442872800, 1442354400, 1443564000, 0, ''),
(96, 'Monitoring and Evaluation Course ', '<!DOCTYPE html>\r\n<html>\r\n<head>\r\n</head>\r\n<body>\r\n<h4>What to Expect.</h4>\r\n<p><em>The M&amp;E course introduces participants to tools and &nbsp; techniques to measure and &nbsp; &nbsp; &nbsp;report projector program results to interested parties, for example donors, government or &nbsp; &nbsp;general public.</em></p>\r\n<h4>Personal benefits</h4>\r\n<p>&bull;Will become familiar with the components of monitoring and evaluation plan</p>\r\n<p>&bull;Methods and tools to conduct data collection, statistical analysis, and reporting&nbsp;</p>\r\n<p>&nbsp;</p>\r\n<h4>&nbsp;Organizational benefits</h4>\r\n<p>&bull;Able to prepare the ground for undertaking monitoring &amp; evaluation and analysis of results</p>\r\n<p>&bull;Undertake effective M&amp;E to improve organizational performance and evidence based decision making</p>\r\n</body>\r\n</html>', 'Yes', 0, 4, 1441144800, 1441058400, 1443564000, 0, ''),
(97, 'Conflict and Peace building Course', '<!DOCTYPE html>\r\n<html>\r\n<head>\r\n</head>\r\n<body>\r\n<h4>What to Expect.</h4>\r\n<p><em>This course is meant to equip participants with knowledge and understanding of theories related to the study of conflict and peace building. The course will examine knowledge, skills, values, and attitudes of managing conflicts constructively and building sustainable peace.</em></p>\r\n<h4>Personal benefits</h4>\r\n<p>&bull;Understanding theories of conflicts and practices of resolution through peace building strategies</p>\r\n<p>&bull;Building skills for analyzing causes of conflicts, mediation, and peace building.</p>\r\n<p>&bull;Develop attitudes which nature the culture of peace&nbsp;</p>\r\n<h4>&nbsp;Organizational benefits</h4>\r\n<p>&bull;Acquire skills for participatory approaches to conflict resolution and peace building</p>\r\n<p>&bull;Design strategies to manage and resolve conflicts within one&rsquo;s community</p>\r\n</body>\r\n</html>', 'Yes', 0, 4, 1441058400, 1441058400, 1443564000, 0, ''),
(98, 'Basic First Aid Course', '<!DOCTYPE html>\r\n<html>\r\n<head>\r\n</head>\r\n<body>\r\n<h4>What to Expect.</h4>\r\n<p><em>This course gives an introduction to first aid essentials both for medical and non-medical staff. &nbsp;It is suited to individuals who wish to gain basic knowledge, skills and attitudes to save lives.</em></p>\r\n<h4>Personal benefits</h4>\r\n<p>&bull;Learn the basic theory and practise for a first responder to emergencies&nbsp;</p>\r\n<p>&bull;Gain insights to emergencies that are not limited to medical, surgical but also traumatic conditions</p>\r\n<p>&nbsp;</p>\r\n<h4>&nbsp;Organizational benefits</h4>\r\n<p>&bull;Gain awareness of relevant knowledge and skills for safer work places</p>\r\n</body>\r\n</html>', 'Yes', 0, 4, 1441058400, 1441058400, 1443564000, 0, ''),
(99, ' Occupational First Aid Course ', '<!DOCTYPE html>\r\n<html>\r\n<head>\r\n</head>\r\n<body>\r\n<h4>What to Expect.</h4>\r\n<p><em>With increased number of emergencies and disasters in work places there is the need to develop knowledge and skills of life saving in the work place. &nbsp;The course focuses on hazards and dangers that are found in the work place. &nbsp;The course targets companies, Industries, colleges, learning institutions, religious institutions and the general public.</em></p>\r\n<h4>Personal benefits</h4>\r\n<p>&bull;Gain first responder skills at the work place to be able to alleviate suffering before skilled medical help arrives. &nbsp;</p>\r\n<p>&bull;Develop personal safety and security for both individual employees and employers at workplace.</p>\r\n<p>&bull;Build skills on best practices to recognise and demonstrate life saving measures e.g. breathing emergencies, bleeding and shock management, splinting, burns and scalds, first aid equipment etc.</p>\r\n<p>&nbsp;</p>\r\n<h4>&nbsp;Organizational benefits</h4>\r\n<p>&bull;Increase capacity at work place to recognise and alleviate suffering&nbsp;</p>\r\n<p>&bull;Develop strategies and systems to effectively manage emergencies and disaster at workplace</p>\r\n<p>&bull;Compliance to the legitimate legal requirements for work place </p>\r\n</body>\r\n</html>', 'Yes', 0, 4, 1441058400, 1441058400, 1443564000, 0, ''),
(100, 'Baby Minder Course', '<!DOCTYPE html>\r\n<html>\r\n<head>\r\n</head>\r\n<body>\r\n<h4>What to Expect.</h4>\r\n<p><em>This course is designed to equip those taking care of children at home, schools, churches, and mosques on how to recognize and manage emergencies when taking care of children. &nbsp;It assists the learner to assess safety measures in the household and identify risks that would expose children to hazards that are life threatening.</em></p>\r\n<h4>Personal benefits</h4>\r\n<p>&bull;Learn how to give sound care to children in case of emergencies.</p>\r\n<p>&bull;Develop skills to keep records and give reports on children in their care.</p>\r\n<h4>&nbsp;Organizational benefits</h4>\r\n<p>&bull;Build confidence for planning, implementing and supporting organisational goals due the established security</p>\r\n</body>\r\n</html>', 'Yes', 0, 4, 1441058400, 1441058400, 1443564000, 0, ''),
(101, 'Basic Fire Safety Course', '<!DOCTYPE html>\r\n<html>\r\n<head>\r\n</head>\r\n<body>\r\n<h4>What to Expect.</h4>\r\n<p><em>In recent decades, the world has experienced an increase of man-made and natural disasters. &nbsp;Now more than ever there is need to understand how to manage fires. &nbsp;This course is designed to give awareness on how to prevent, suppress and evacuate in the case of a fire outbreak. &nbsp;The course targets every person at all levels.</em></p>\r\n<h4>Personal benefits</h4>\r\n<p>&bull;Learn basic knowledge, skills and attitudes on fire prevention measures as well as actions necessary for minimizing impact in the event of fire.</p>\r\n<h4><span style="font-weight: normal;">&bull;Learn fire emergency response procedures.</span></h4>\r\n<h4>&nbsp;Organizational benefits</h4>\r\n<p>&bull;Gain awareness of relevant knowledge and skills for safer work places.</p>\r\n<p>&bull;Avoid destruction of property and loss of lives.</p>\r\n</body>\r\n</html>', 'Yes', 0, 4, 1438380000, 1441058400, 1443564000, 0, ''),
(102, 'Fire Wardens or Marshal Course', '<!DOCTYPE html>\r\n<html>\r\n<head>\r\n</head>\r\n<body>\r\n<h4>What to Expect.</h4>\r\n<p><em>This course is a technical course aimed at the learner to gain expertise in fire incidences. &nbsp;It includes ways of assessing risks and being prepared for fires. &nbsp;It gives the technical know-how on investigating the causes of fires and preventive measures. The course targets corporates, institutions, Non-governmental organization (NGOs), Community based organization (CBOs), and faith based organization (FBOs), Government Ministries, Parastatals, Factories and general public.</em></p>\r\n<h4>Personal benefits</h4>\r\n<p>&bull;earn correct skills as a Fire Marshal on how to handle fire disasters.</p>\r\n<p>&bull;Develop risk management; regulations, structures and systems skills manage fire incidents in workplace and communities.</p>\r\n<p>&bull;Build skills to conduct fire audits and assessments</p>\r\n<h4>&nbsp;Organizational benefits</h4>\r\n<p>&bull;Build competent team members to manage, monitor and carry out audits for fire safety.</p>\r\n<p>&bull;Build capacity to conduct emergency drills</p>\r\n<p>&bull;Enhance trained staff to efficiently inspect and operate fire safety equipment&nbsp;</p>\r\n<p>&bull;Build capacity for staff to deal with small fires before they become costly</p>\r\n<p>&bull;Enhance capacity to staff to keep record of past fire incidence</p>\r\n<p>&bull;Compliance to the legitimate legal requirements for work place </p>\r\n</body>\r\n</html>', 'Yes', 0, 4, 1438380000, 1441058400, 1443564000, 0, ''),
(103, 'Occupational Safety and Health Course', '<!DOCTYPE html>\r\n<html>\r\n<head>\r\n</head>\r\n<body>\r\n<h4>What to Expect.</h4>\r\n<p><em>This course gives the learner knowledge and skills for occupational safety, hygiene, health and management. &nbsp;It provides concepts that help the learner to set standards in the work place that promote safety.</em></p>\r\n<h4>Personal benefits</h4>\r\n<p>&bull;Reduction of injuries at work place</p>\r\n<p>&bull;Increased work output&nbsp;</p>\r\n<p>&bull;Minimization of work related disability&nbsp;</p>\r\n<h4>&nbsp;Organizational benefits</h4>\r\n<p>&bull;Compliance to the legitimate legal requirements for work place&nbsp;</p>\r\n<p>&bull;Minimize accidents in the work place</p>\r\n<p>&bull;Promote safe work procedures&nbsp;</p>\r\n<p>&bull;Decreased operation costs</p>\r\n</body>\r\n</html>', 'Yes', 0, 4, 1438380000, 1441058400, 1443564000, 0, ''),
(104, 'Advanced Trauma Life Support ', '<!DOCTYPE html>\r\n<html>\r\n<head>\r\n</head>\r\n<body>\r\n<h4>What to Expect.</h4>\r\n<p><em>This course is designed for the learners with medical background and introduces concepts on how to care for injury victims. &nbsp;It gives the learner new concepts on how to assess, provide immediate and subsequent care following severe injuries. It also gives advanced techniques of bleeding control, Team leading and resuscitation co-ordination.</em></p>\r\n<h4>Personal benefits</h4>\r\n<p>&bull;Strength skills to assess and intervene following a severe injury</p>\r\n<p>&bull;Enhance capacity to participate in a resuscitation team</p>\r\n<h4>&nbsp;Organizational benefits</h4>\r\n<p>&bull;Equip the facility with skills to effectively manage accidents involving many victims</p>\r\n</body>\r\n</html>', 'Yes', 0, 4, 1438380000, 1441058400, 1443564000, 0, ''),
(105, 'Advanced Cardic Life Support ', '<!DOCTYPE html>\r\n<html>\r\n<head>\r\n</head>\r\n<body>\r\n<h4>What to Expect.</h4>\r\n<p><em>This is for health workers and has advanced specialized techniques required to further save life following heart problems. It is an advanced course that highlights the importance of team dynamics and communication, systems of care and immediate post-cardiac arrest care. &nbsp;The course targets medical professionals such as Medical Officers, Nurses and Clinical Officers. &nbsp;&nbsp;</em></p>\r\n<ul class="gkBullet2">\r\n<li>&nbsp;Learn skills to recognize and provide care of victims with cardiac problems by use of equipment and techniques at an advanced level.</li>\r\n<li>Develop skills to effectively participate in a resuscitation scenario</li>\r\n<li>Organizational benefits</li>\r\n<li>&nbsp;Strength the capacity to provide care for cardiac victims.&nbsp;</li>\r\n<li>Enhance continuity of care after resuscitation</li>\r\n</ul>\r\n</body>\r\n</html>', 'Yes', 0, 4, 1438380000, 1441058400, 1443564000, 0, ''),
(106, 'Emergency Medical Techician ', '<!DOCTYPE html>\r\n<html>\r\n<head>\r\n</head>\r\n<body>\r\n<p>This module provides the learner with opportunity to understand the emergency medical services systems, personal safety, medical/legal and ethics, and lifting and moving patients.&nbsp;</p>\r\n<h4>Course Benefits:</h4>\r\n<ul class="gkBullet2">\r\n<li>Learn skills to recognize and provide care of victims with cardiac problems by use of equipment and techniques at an advanced level.</li>\r\n<li>Develop skills to effectively participate in a resuscitation scenario</li>\r\n</ul>\r\n</body>\r\n</html>', 'Yes', 0, 4, 1438380000, 1441058400, 1443564000, 0, ''),
(107, 'Basic Life Support ', '<!DOCTYPE html>\r\n<html>\r\n<head>\r\n</head>\r\n<body>\r\n<p>This is a course that gives an advanced skill to the individuals to be able to be able to provide life savings skills especially for victims with compromised airway, breathing and circulation. &nbsp;More often than not a layman may find a victim who is not breathing or the heart is not functioning well and may not know how to intervene. &nbsp;It provides basic medical concepts such as basic resuscitation and cardiopulmonary techniques. The course targets health and non-health care providers</p>\r\n<h4><em>Personal benefits</em></h4>\r\n<p><em>&bull;Recognize several life-threatening emergencies</em></p>\r\n<p><em>&bull;Develop skills to undertake Cardiopulmonary Resuscitation (CPR) to victims of all ages, use an AED, and relieve choking in a safe, timely and effective manner.</em></p>\r\n<h4><em>Organizational benefits</em></h4>\r\n<p><em>&bull;Increase capacity on how to recognize several life threatening emergencies</em></p>\r\n<p><em>&bull;Improving chances of survival following resuscitation after airway, breathing and circulation compromise</em></p>\r\n<p><em>&bull;Enhance continuity of care after resuscitation</em></p>\r\n</body>\r\n</html>', 'Yes', 0, 4, 1438380000, 1441058400, 1443564000, 0, ''),
(108, 'Effective Humanitarian Leadership Programme ', '<!DOCTYPE html>\r\n<html>\r\n<head>\r\n</head>\r\n<body>\r\n<p>Many leaders are better at project management than their own leadership development. The EHL programme will help you to use your well-practiced project skills by applying them to your own leadership development. In short, you will be embarking on a new project &ndash; Project You. The EHL is aimed at directors and senior managers with several years of experience of leadership; their role now, or in the future, will involve significant strategic level contribution.</p>\r\n<h4>The program addresses the gap by:</h4>\r\n<ul class="gkBullet2">\r\n<li>Including robust profiling and needs assessment, ahead of two intensive training sessions in Nairobi, plus follow up coaching, evaluation and strategic level learning.&nbsp;</li>\r\n<li>Tailoring to individual, team and country level needs&nbsp;</li>\r\n<li>Carrying out training sessions in groups to develop wider understanding and commitment to shared standards.&nbsp;</li>\r\n<li>Deepening positive attitudes, behaviours and commitment towards high achievement and standards of leadership.&nbsp;</li>\r\n<li>Focusing on good governance, accountability and transparency.</li>\r\n<li>Developing critical and reflective thinking ability in Humanitarian leaders</li>\r\n<li>Exploring responsible decision making and personal accountability</li>\r\n<li>Demonstrating understanding of group dynamics and effective group-work</li>\r\n<li>Developing a range of leadership skills and abilities, such as effectively leading change, resolving conflict and motivating others</li>\r\n<li>The EHL is aimed at directors and senior managers with several years of experience of leadership; their role now, or in the future, will involve significant strategic level contribution.&nbsp;</li>\r\n</ul>\r\n<h4>How to register</h4>\r\n<p>send an email to <a href="mailto:training@redcross.or.ke">training@redcross.or.ke</a>&nbsp;for more details.</p>\r\n</body>\r\n</html>', 'Yes', 0, 5, 1438380000, 1445230800, 1445576400, 0, '{"filename":"957_EHLP  Application form - 2015.pdf","document":"EHLP  Application form - 2015.pdf"}'),
(109, 'International Health Leadership Development Programme ', '<!DOCTYPE html>\r\n<html>\r\n<head>\r\n</head>\r\n<body>\r\n<p>The programme is designed to learn and develop managerial and leadership practices. As such it is carefully structured to achieve this outcome (refer to the diagram for an overview). The programme will kick off with a pre-meeting, which will be virtual (a video conference or webinar). The meeting will begin with a presentation of the philosophy and key concepts of the programme. Participants will be given two key readings to read and discuss (in your organisational group) before you attend the first module. This will enable a quick entry into the ethos of the programme</p>\r\n</body>\r\n</html>', 'Yes', 0, 5, 1438380000, 1440997200, 1443502800, 0, '{"filename":"747_International Health Leadership Programme-Flier(General).pdf","document":"International Health Leadership Programme-Flier(General).pdf"}'),
(110, 'Disaster Management Course', '<!DOCTYPE html>\r\n<html>\r\n<head>\r\n</head>\r\n<body>\r\n<h4>What to Expect.</h4>\r\n<p><em>This is an intense course that gives the technical details on how to actively manage a disaster. &nbsp;It involves preparedness, cycles, risk reduction and response. &nbsp;It also teaches the learner on how to promote disaster awareness and education to the community. &nbsp;It exposes the learner on global aspects, media management and how to assess the impacts caused. &nbsp;It also has a component of health issues and how to incorporate emergency medicine into the management of disasters. The Course targets representatives from relevant Government ministries/departments, Emergency Service Providers, Humanitarian/relief workers, disaster managers, and the general public</em></p>\r\n<h4>Personal benefits</h4>\r\n<p>&bull;Learn knowledge, skills and attitudes in disaster management within the context of humanitarian assistance and sustainable development, while respecting human dignity.</p>\r\n<p>&bull;Develop effective strategies and systems for disaster Risk Reduction, Preparedness, Response and recovery.</p>\r\n<p>&bull;Demonstrate knowledge, skills and expertise in managing water sanitation and hygiene in emergency situations.</p>\r\n<p>&bull;Develop effective skills and expertise in provision of First Aid during emergencies.</p>\r\n<p>&bull;Experience in conducting psychosocial support and Tracing of missing persons</p>\r\n<p>&nbsp;</p>\r\n<h4>&nbsp;Organizational benefits</h4>\r\n<p>&bull;Build capacities and confidence for planning, implementing, supporting and sustaining programmes</p>\r\n</body>\r\n</html>', 'Yes', 0, 5, 1438380000, 1441058400, 1443564000, 0, ''),
(111, 'Health Emergencies in Large Populations', '<!DOCTYPE html>\r\n<html>\r\n<head>\r\n</head>\r\n<body>\r\n<p>The course provides a multi-cultural and multi-disciplinary learning experience geared towards addressing the key thematic areas in health taking into account emerging issues globally. So far, the course has been facilitated in various parts of Latin America, Asia, Western and Eastern Europe and Africa.</p>\r\n<p>The course objective is to train professionals in the principles and practice of humanitarian action in response to disasters and humanitarian crises, with a focus on needs, public health, health care and ethics and contribute to academic training, research and development, in humanitarian action.</p>\r\n<p>Since 1986, approximately 2500 health professionals and humanitarian aid workers from the International Red Cross and Red Crescent Movement, United Nations agencies, NGOs, ministries of Health, Armed Forces Medical Services and academic institutions have attended the HELP Course. It is intended for health professionals including doctors, nurses, nutritionists, environmental engineers, epidemiologists and Public Health Officers. Participants with experience in humanitarian response, those working in countries affected by war or natural disasters and in management of humanitarian assistance or emergency programmes are encouraged to apply.</p>\r\n</body>\r\n</html>', 'Yes', 0, 6, 1438380000, 1441058400, 1443564000, 0, ''),
(112, 'Nursing Course', '<!DOCTYPE html>\r\n<html>\r\n<head>\r\n</head>\r\n<body>\r\n<h4>What to Expect.</h4>\r\n<p><em>ICHA plans to offer a nursing program that will provide opportunities for development of students and faculty members as practitioners enhanced by local and internationally accredited programs. The programs will be enriched by faculty and student exchanges, international service learning projects, enrolment of local and international students. ICHA plans to partner with local and international institutions to offer a Diploma in Nursing.</em></p>\r\n</body>\r\n</html>', 'Yes', 0, 7, 1443086600, 1443650400, 1446246000, 0, ''),
(113, 'Paramedicine Course', '<!DOCTYPE html>\r\n<html>\r\n<head>\r\n</head>\r\n<body>\r\n<h4>What to Expect.</h4>\r\n<p><em>Paramedicine program prepares Paramedic professionals with an advanced scope of practice to meet the needs of the health care system. This program addresses not only the operational/procedural skills but the skills required to make critical and sound decisions, confidence to manage a variety of situations, effective verbal and written communications skills, interpersonal skills, leadership skills and to make healthy lifestyle decisions to maintain wellbeing.</em></p>\r\n<p><em>ICHA plans to partner with international institutions offer both Certificate, Diploma and Advanced Diploma in Paramedicine.</em></p>\r\n</body>\r\n</html>', 'Yes', 0, 7, 1443086789, 1443589200, 1446181200, 0, '{"filename":"665_9720.pdf","document":"9720.pdf"}'),
(116, 'Humanitarian Response', '<!DOCTYPE html>\n<html>\n<head>\n</head>\n<body>\n<p>INTRODUCTION</p>\n<p>The changing humanitarian landscape requires a change in capacities and knowledge of humanitarian actors. Leaders and managers in the various humanitarian organizations will benet from a multi disciplinary understanding of the changing humanitarian landscape.</p>\n<p>A multi disciplinary approach is not saddled with specialization and sector specic content. It will require examination of the changing humanitarian landscape and its implication for policy and strategy using a multi sector approach. This Basic Humanitarian Course (BHC) will provide participants with an opportunity to explore processes, events and policy debates shaping the humanitarian landscape as managers and decision makers in their various institutions.</p>\n<p>The course taught in collaboration with the York University provides humanitarian actors an opportunity to assess, learn and reect on critical aspects of humanitarian work. The course provides practitioners with a structured system to learn from and engage with peers while beneting from lectures from experts. The course is delivered with the practitioner in mind; it provides an opportunity for the participant to reect on his or her own experience while beneting from exchange of best practices from peers. The course is therefore grounded on a discourse on the current challenges and opportunities characterizing the humanitarian landscape. Since the course is multi disciplinary, it will be suitable for professionals from any discipline and academic backgrounds.</p>\n<p>CURRICULUM OUTLINE</p>\n<p>The ve days will examine the conceptual issues in humanitarian aairs within social,</p>\n<p>studies. The participants examine and analyse the key clusters in humanitarian response &ndash;</p>\n<p>protection, food and nutrition, health, shelter and water and sanitation hygiene (WASH)</p>\n<p>humanitarian responses will be analysed. Foundation skills for monitoring and evaluation</p>\n<p>will be taught where participants will adapt them according to their contexts.</p>\n<p>COURSE LEARNING OUTCOMES</p>\n<p>to respond to humanitarian needs locally and globally</p>\n<p>and practice</p>\n<p>3. Critically engage with the past, present and potential future of the humanitarian discourse</p>\n<p>COURSE AGENDA</p>\n<p>DAY 01: HUMANITARIANISM</p>\n<p>1330: Past-present-future of humanitarianism</p>\n<p>1430: Humanitarian principles, norms and frameworks</p>\n<p>1600: Introduction to participant project</p>\n<p>DAY 02: HUMANITARIAN RESPONSES</p>\n<p>0900: Challenges in the changing humanitarian landscape</p>\n<p>1000: Humanitarian responses in the African context: challenges and opportunities</p>\n<p>Conict and disaster impact analysis</p>\n<p>DAY 03: PROTECTION AND ASSISTANCE &amp; MONITORING AND EVALUATION OF HUMANITARIAN RESPONSES</p>\n<p>0900: Introduction to designing humanitarian responses</p>\n<p>1000: Protection issues in African humanitarian contexts</p>\n<p>1330: Monitoring and evaluation in humanitarian responses</p>\n<p>DAY 04: HUMANITARIAN PROGRAMMING: AN INTRODUCTION</p>\n<p>0900: Humanitarian clusters: food and nutrition, health, shelter and water and sanitation hygiene</p>\n<p>1100: Project management &ndash; donors, agencies and people</p>\n<p>DAY 05: HOW DO WE KNOW THAT WE ARE DOING BETTER?</p>\n</body>\n</html>', 'Yes', 0, 5, 1445099958, 0, 0, 0, '{"filename":"484_Humanitarian Response Course Brochure (2015).pdf","document":"Humanitarian Response Course Brochure (2015).pdf"}');

-- --------------------------------------------------------

--
-- Table structure for table `currency`
--

CREATE TABLE IF NOT EXISTS `currency` (
  `currencyid` int(11) NOT NULL,
  `currencyname` text NOT NULL,
  `currencycode` varchar(10) NOT NULL,
  `status` text NOT NULL
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `currency`
--

INSERT INTO `currency` (`currencyid`, `currencyname`, `currencycode`, `status`) VALUES
(1, 'Kenya Shillings', 'KSH', 'default');

-- --------------------------------------------------------

--
-- Table structure for table `documents`
--

CREATE TABLE IF NOT EXISTS `documents` (
  `docid` int(11) NOT NULL,
  `filename` text NOT NULL,
  `uploaddate` int(11) NOT NULL,
  `docname` varchar(300) DEFAULT NULL,
  `documenttype` text
) ENGINE=MyISAM AUTO_INCREMENT=982 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `documents`
--

INSERT INTO `documents` (`docid`, `filename`, `uploaddate`, `docname`, `documenttype`) VALUES
(907, '817SSRN-id2334313.pdf', 1384023956, 'SSRN-id2334313.pdf', 'pdf'),
(905, '452SSRN-id2334313.pdf', 1384022910, 'SSRN-id2334313.pdf', 'pdf'),
(906, '452SSRN-id2334313.pdf', 1384022910, 'SSRN-id2334313.pdf', 'pdf'),
(919, '292ComplementaritybetweentheAfricancommissionandtheafricancourt.pdf', 1384164185, 'Complementarity between the African commission and the african court.pdf', 'pdf'),
(910, '3SSRN-id2334343.pdf', 1384099615, 'SSRN-id2334343.pdf', 'pdf'),
(911, '431SSRN-id2334353.pdf', 1384099758, 'SSRN-id2334353.pdf', 'pdf'),
(912, '988SSRN-id2335338.pdf', 1384099877, 'SSRN-id2335338.pdf', 'pdf'),
(914, '748SSRN-id2347360.pdf', 1384100181, 'SSRN-id2347360.pdf', 'pdf'),
(920, '830SSRN-id2334343.pdf', 1384167395, 'SSRN-id2334343.pdf', 'pdf'),
(916, '164ComplementaritybetweentheAfricancommissionandtheafricancourt.pdf', 1384100509, 'Complementarity between the African commission and the african court.pdf', 'pdf'),
(926, '629SSRN-id2239412.pdf', 1391534803, 'SSRN-id2239412.pdf', 'pdf'),
(918, '588SSRN-id2239412.pdf', 1384101074, 'SSRN-id2239412.pdf', 'pdf'),
(922, '445SSRN-id929012.pdf', 1384168846, 'SSRN-id929012.pdf', 'pdf'),
(923, '51SSRN-id1012910.pdf', 1384170240, 'SSRN-id1012910.pdf', 'pdf'),
(924, '625SSRN-id691642.pdf', 1389585101, 'SSRN-id691642.pdf', 'pdf'),
(927, '170214834341-President-Uhuru-Kenyatta-s-State-of-the-Nation-Address.pdf', 1397634933, '214834341-President-Uhuru-Kenyatta-s-State-of-the-Nation-Address.pdf', 'pdf'),
(928, '398ApproachToMIS.pdf', 1397638031, 'ApproachToMIS.pdf', 'pdf'),
(929, '942ApproachToMIS.pdf', 1397638250, 'ApproachToMIS.pdf', 'pdf'),
(930, '89DavidNdii9thApril2014.pdf', 1397651467, 'David Ndii 9th April 2014.pdf', 'pdf'),
(931, '790ApproachToMIS.pdf', 1398672035, 'ApproachToMIS.pdf', 'pdf'),
(950, 'testdoc2.pdf', 1401135215, 'testdoc2.pdf', 'marked_paper'),
(939, 'Complementarity between the African commission and the african court.docx', 1399038181, 'Complementarity between the African commission and the african court.docx', 'submitted_doc'),
(941, 'SSRN-id2334313.pdf', 1399121997, 'SSRN-id2334313.pdf', 'marked_paper'),
(944, 'ID_SSRN-id2239412.pdf', 1399193686, 'ID_SSRN-id2239412.pdf', 'ID'),
(945, 'CV_SSRN-id2336669.pdf', 1399193686, 'CV_SSRN-id2336669.pdf', 'CV'),
(951, 'ID_TI Survey latest 2012.pdf', 1401775048, 'ID_TI Survey latest 2012.pdf', 'ID'),
(952, 'CV_Njeru Sarah Makena KSMS Admission letter.pdf', 1401775048, 'CV_Njeru Sarah Makena KSMS Admission letter.pdf', 'CV'),
(953, 'ID_Article 3 2014.docx', 1401776778, 'ID_Article 3 2014.docx', 'ID'),
(954, 'CV_Defer Letter.pdf', 1401776778, 'CV_Defer Letter.pdf', 'CV'),
(956, 'ID_Gatwiri CV.pdf', 1402300788, 'ID_Gatwiri CV.pdf', 'ID'),
(957, 'CV_Njeru Sarah Makena KSMS Admission letter.pdf', 1402300788, 'CV_Njeru Sarah Makena KSMS Admission letter.pdf', 'CV'),
(959, 'ID_SSRN-id2334343.pdf', 1402428567, 'ID_SSRN-id2334343.pdf', 'ID'),
(960, 'CV_SSRN-id2348202.pdf', 1402428567, 'CV_SSRN-id2348202.pdf', 'CV'),
(961, 'ID_FEE STRUCTURE IN KSHS - JKUAT MASTERS.pdf', 1402552529, 'ID_FEE STRUCTURE IN KSHS - JKUAT MASTERS.pdf', 'ID'),
(962, 'CV_Defer Letter.pdf', 1402552529, 'CV_Defer Letter.pdf', 'CV'),
(963, 'ID_SSRN-id2334313.pdf', 1402554646, 'ID_SSRN-id2334313.pdf', 'ID'),
(964, 'CV_SSRN-id2239412.pdf', 1402554646, 'CV_SSRN-id2239412.pdf', 'CV'),
(979, '9720.pdf', 1443189043, '9720.pdf', 'pdf'),
(966, 'ID_Defer Letter.pdf', 1410179835, 'ID_Defer Letter.pdf', 'ID'),
(967, 'CV_SARAH MAKENA N CV.pdf', 1410179835, 'CV_SARAH MAKENA N CV.pdf', 'CV'),
(968, 'ID_Defer Letter.pdf', 1410247569, 'ID_Defer Letter.pdf', 'ID'),
(969, 'CV_SARAH MAKENA N CV.pdf', 1410247569, 'CV_SARAH MAKENA N CV.pdf', 'CV'),
(970, 'ID_SSRN-id929012.pdf', 1410255883, 'ID_SSRN-id929012.pdf', 'ID'),
(971, 'CV_SSRN-id2334353.pdf', 1410255883, 'CV_SSRN-id2334353.pdf', 'CV'),
(972, 'ID_FEE STRUCTURE IN KSHS - JKUAT MASTERS.pdf', 1410509619, 'ID_FEE STRUCTURE IN KSHS - JKUAT MASTERS.pdf', 'ID'),
(973, 'CV_Gatwiri CV.pdf', 1410509619, 'CV_Gatwiri CV.pdf', 'CV'),
(974, 'ID_Career.pdf', 1410869279, 'ID_Career.pdf', 'ID'),
(975, 'CV_International Finance.pdf', 1410869279, 'CV_International Finance.pdf', 'CV'),
(976, 'ID_DAAD FILLED FORM.pdf', 1410935584, 'ID_DAAD FILLED FORM.pdf', 'ID'),
(977, 'CV_Defer Letter.pdf', 1410935584, 'CV_Defer Letter.pdf', 'CV'),
(978, 'tullow.pdf', 1441876907, 'tullow.pdf', 'pdf'),
(980, 'ID_9720.pdf', 1443415479, 'ID_9720.pdf', 'ID'),
(981, 'CV_9720.pdf', 1443415479, 'CV_9720.pdf', 'CV');

-- --------------------------------------------------------

--
-- Table structure for table `events`
--

CREATE TABLE IF NOT EXISTS `events` (
  `eventid` int(11) NOT NULL,
  `title` varchar(300) NOT NULL,
  `description` text NOT NULL,
  `source` varchar(100) NOT NULL,
  `sourceid` int(11) NOT NULL,
  `startdate` int(11) NOT NULL,
  `enddate` int(11) NOT NULL,
  `venue` varchar(200) DEFAULT NULL,
  `focusarea` int(11) NOT NULL
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `events`
--

INSERT INTO `events` (`eventid`, `title`, `description`, `source`, `sourceid`, `startdate`, `enddate`, `venue`, `focusarea`) VALUES
(6, 'First Aid Training', '<!DOCTYPE html>\r\n<html>\r\n<head>\r\n</head>\r\n<body>\r\n<p>These courses are for anyone who wants to learn first aid,</p>\r\n<p>from basic everyday skills to coping with emergencies.</p>\r\n</body>\r\n</html>', 'course', 98, 1446094800, 1446094800, 'NoneICHA CENTER ', 0);

-- --------------------------------------------------------

--
-- Table structure for table `focusareas`
--

CREATE TABLE IF NOT EXISTS `focusareas` (
  `areaid` int(11) NOT NULL,
  `name` varchar(200) DEFAULT NULL,
  `alias` text NOT NULL
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `focusareas`
--

INSERT INTO `focusareas` (`areaid`, `name`, `alias`) VALUES
(1, 'Training and Education', 'training'),
(2, 'Policy Dialogue and Knowledge Management', 'policy'),
(3, 'Research, Monitoring and Evaluation', 'research'),
(4, 'Strategy Development and Consultancy', 'strategy');

-- --------------------------------------------------------

--
-- Table structure for table `frontpage`
--

CREATE TABLE IF NOT EXISTS `frontpage` (
  `frontid` int(11) NOT NULL,
  `articleid` int(11) NOT NULL,
  `order` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `guest`
--

CREATE TABLE IF NOT EXISTS `guest` (
  `guestid` int(11) NOT NULL,
  `sessionid` varchar(100) NOT NULL COMMENT 'the session id for the guest user',
  `userip` varchar(15) NOT NULL COMMENT 'the user IP'
) ENGINE=MyISAM AUTO_INCREMENT=17 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `guest`
--

INSERT INTO `guest` (`guestid`, `sessionid`, `userip`) VALUES
(15, '931f6adffeda4a8c049aa1f94ae2616d', '127.0.0.1'),
(14, '8bc20c571e95e294d8485041a4490e90', '127.0.0.1'),
(16, '3f9ef0bc319dcc030c71e51637cbf716', '::1');

-- --------------------------------------------------------

--
-- Table structure for table `imgmanager`
--

CREATE TABLE IF NOT EXISTS `imgmanager` (
  `imgid` int(11) NOT NULL,
  `imgname` varchar(200) NOT NULL,
  `filename` varchar(200) NOT NULL,
  `imgcategory` text NOT NULL,
  `articleid` int(11) NOT NULL
) ENGINE=MyISAM AUTO_INCREMENT=264 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `imgmanager`
--

INSERT INTO `imgmanager` (`imgid`, `imgname`, `filename`, `imgcategory`, `articleid`) VALUES
(234, 'mail.jpg', 'mail.jpg', 'banner', 696),
(242, 'Admin-icon.png', 'Admin-icon.png', 'profilepic', 1),
(259, 'ProfKarega.jpg', 'ProfKarega.jpg', 'profilepic', 7),
(246, 'banner3.jpg', 'banner3.jpg', 'banner', 1113),
(253, 'banner1.jpg', 'banner1.jpg', 'banner', 1111),
(254, 'Kibara.jpg', 'Kibara.jpg', 'profilepic', 4),
(249, 'Sarah.jpg', 'Sarah.jpg', 'profilepic', 5),
(250, 'mary.jpg', 'mary.jpg', 'profilepic', 6),
(260, 'Uzma_Alam.jpg', 'Uzma_Alam.jpg', 'profilepic', 12),
(261, 'kisia.jpg', 'kisia.jpg', 'profilepic', 2),
(262, 'idris.jpg', 'idris.jpg', 'profilepic', 10),
(263, 'Agnes (1).jpg', 'Agnes (1).jpg', 'profilepic', 11);

-- --------------------------------------------------------

--
-- Table structure for table `instructor_course`
--

CREATE TABLE IF NOT EXISTS `instructor_course` (
  `instructor_course_id` int(11) NOT NULL,
  `instructor_id` int(11) DEFAULT NULL,
  `course_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `menugroups`
--

CREATE TABLE IF NOT EXISTS `menugroups` (
  `groupid` int(11) NOT NULL,
  `menuname` text NOT NULL,
  `accesslevelid` varchar(100) NOT NULL
) ENGINE=MyISAM AUTO_INCREMENT=9 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `menugroups`
--

INSERT INTO `menugroups` (`groupid`, `menuname`, `accesslevelid`) VALUES
(4, 'Superadmin', '7'),
(5, 'Admin', '8'),
(6, 'Frontend', '9'),
(7, 'Student', '14'),
(8, 'Instructor', '15');

-- --------------------------------------------------------

--
-- Table structure for table `menus`
--

CREATE TABLE IF NOT EXISTS `menus` (
  `menuid` int(11) NOT NULL,
  `linkname` text NOT NULL,
  `linkalias` varchar(100) NOT NULL,
  `menulink` varchar(200) NOT NULL,
  `linkorder` int(11) NOT NULL,
  `menugroup` int(11) NOT NULL,
  `enabled` varchar(20) NOT NULL,
  `parentid` int(11) NOT NULL,
  `home` varchar(50) NOT NULL
) ENGINE=MyISAM AUTO_INCREMENT=107 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `menus`
--

INSERT INTO `menus` (`menuid`, `linkname`, `linkalias`, `menulink`, `linkorder`, `menugroup`, `enabled`, `parentid`, `home`) VALUES
(92, 'My Students', 'my-students', '?instructor=com_instructor&folder=students&file=showstudents', 3, 8, 'no', 0, 'no'),
(91, 'My Courses', 'my-courses', '?instructor=com_instructor&folder=courses&file=showcourses', 1, 8, '', 0, 'no'),
(90, 'Dashboard', 'dashboard', '?instructor=com_instructor&folder=frontpage&file=frontpage', 0, 8, '', 0, 'yes'),
(89, 'My Tests and Assignments', 'my-tests-and', '?student=com_students&folder=tests_assignments&file=showassignments', 4, 7, 'yes', 0, 'no'),
(88, 'My Courses', 'my-courses', '?student=com_students&folder=courses&file=showcourses', 2, 7, 'yes', 0, 'no'),
(87, 'My Profile', 'my-profile', '?student=com_students&folder=profile&file=myprofile', 1, 7, 'no', 0, 'no'),
(86, 'Dashboard', 'dashboard', '?student=com_students&folder=frontpage&file=frontpage', 0, 7, 'no', 0, 'yes'),
(85, 'About the Institute', 'about-the-institute', '?content=com_articles&artid=8', 15, 6, 'yes', 73, 'no'),
(84, 'Courses', 'courses', '#', 17, 6, '', 74, 'no'),
(83, 'History', 'history', '?content=com_articles&artid=1126', 16, 6, 'yes', 73, 'no'),
(82, 'Archived Consultancies', 'archived-consultancies', '#', 14, 6, 'no', 77, 'no'),
(80, 'Policy Dialogue Events', 'policy-dialogue-events', '?content=com_events&folder=same&file=showevents&filter=policy', 12, 6, '', 75, 'no'),
(76, 'Research', 'research', '#', 9, 6, 'yes', 71, 'no'),
(75, 'Policy And Advocacy', 'policy-and-advocacy', '#', 8, 6, 'yes', 71, 'no'),
(74, 'Training and Education', 'training-and-education', '?content=com_courses&folder=same&file=showallcourses', 7, 6, 'yes', 71, 'no'),
(73, 'Our Institute', 'our-institute', '?content=com_articles&artid=8', 1, 6, 'Yes', 0, ''),
(72, 'Publications', 'publications', '?content=com_publications&folder=same&file=showpublications&show=publication', 6, 6, 'yes', 68, 'no'),
(71, 'Our Programs', 'our-programs', '?content=com_courses&folder=same&file=showallcourses', 2, 6, 'yes', 0, 'no'),
(70, 'Articles', 'articles', '?admin=com_admin&folder=articles&file=showarticles', 10, 5, 'Yes', 0, ''),
(69, 'Contact Us', 'contacts-us', '?content=com_contacts&folder=same&file=showcontacts', 5, 6, 'Yes', 0, ''),
(68, 'Resources & Tools', 'resources-tools', '?content=com_publications&folder=same&file=allpublications', 3, 6, 'yes', 0, 'no'),
(66, 'Dashboard', 'dashboard', '?admin=com_frontpage', 0, 4, 'Yes', 0, 'no'),
(67, 'News And Events', 'news-and-events', '?content=com_news&folder=same&file=shownews&filter=all', 4, 6, 'yes', 0, 'no'),
(65, 'Home', 'home', '?content=com_frontpage', 0, 6, 'yes', 0, 'yes'),
(64, 'Students', 'students', '?admin=com_admin&folder=students&file=showstudents', 9, 5, 'yes', 0, 'no'),
(62, 'Instructors', 'instructors', '?admin=com_admin&folder=contributors&file=showcontributors', 8, 5, 'Yes', 0, ''),
(61, 'Users', 'users', '?admin=com_admin&folder=users&file=showusers', 2, 4, 'Yes', 0, ''),
(59, 'Frontpage', 'frontpage', '?admin=com_admin&folder=frontpage&file=showfront', 2, 5, 'Yes', 0, ''),
(58, 'Site Activity', 'site-activity', '?admin=com_admin&folder=activity&file=showactivity', 3, 4, 'Yes', 0, ''),
(56, 'Menus', 'menus', '?admin=com_admin&folder=navigation&file=shownavigation', 15, 5, 'yes', 0, 'no'),
(79, 'Events', 'events', '?admin=com_admin&folder=events&file=showevents', 13, 5, 'Yes', 0, ''),
(54, 'Courses & Units', 'courses-units', '?admin=com_admin&folder=courses&file=showcourses', 2, 5, 'yes', 0, 'no'),
(53, 'Resources & Tools', 'resources-tools', '?admin=com_admin&folder=publications&file=showpublications', 12, 5, 'Yes', 0, ''),
(52, 'Dashboard', 'dashboard', '?admin=com_admin&folder=frontpage&file=frontpage', 1, 5, 'yes', 0, 'yes'),
(93, 'Tests and Assignments', 'tests-and-assignments', '?instructor=com_instructor&folder=tests_assignments&file=showtests', 6, 8, 'yes', 0, 'no'),
(103, 'System Files', 'system-files', '?admin=com_admin&folder=files&file=showallfiles', 14, 5, '', 0, 'no'),
(95, 'My Student Grades', 'my-student-grades', '?instructor=com_instructor&folder=students&file=showstudentgrades', 4, 8, 'no', 0, 'no'),
(97, 'My Graded Papers', 'my-graded-papers', '?student=com_students&folder=grades&file=showgrades', 3, 7, 'yes', 0, 'no'),
(98, 'Fees Statements', 'fees-statements', '?student=com_students&folder=fees&file=showfees', 5, 7, 'yes', 0, 'no'),
(100, 'Student Submissions', 'student-submissions', '?instructor=com_instructor&folder=students&file=showsubmissions', 5, 8, 'yes', 0, 'no'),
(101, 'Finances', 'finances', '?admin=com_admin&folder=finances&file=showfinances', 13, 5, 'yes', 0, 'no'),
(102, 'Contact Administration', 'contact-administration', '?student=mod_feedback', 7, 7, '', 0, 'no'),
(104, 'Our Experts', 'our-experts', '?content=com_profiles&folder=same&file=showprofile&filter=all', 18, 6, 'yes', 73, '0'),
(105, 'Research Papers', 'research-papers', '?content=com_publications&folder=same&file=showpublications&show=researchpaper', 7, 6, 'yes', 68, 'no'),
(106, 'Categories', 'categories', '?admin=com_admin&folder=articles&file=showcategories', 11, 5, 'Yes', 0, 'no');

-- --------------------------------------------------------

--
-- Table structure for table `paymentconfirmation`
--

CREATE TABLE IF NOT EXISTS `paymentconfirmation` (
  `paymentconfirmations_id` int(11) NOT NULL,
  `tracking_id` varchar(400) DEFAULT NULL,
  `merchant_reference` int(11) DEFAULT NULL,
  `paymentdate` int(11) DEFAULT NULL,
  `amount` varchar(100) DEFAULT NULL,
  `curid` int(11) DEFAULT NULL,
  `paiditem` varchar(100) DEFAULT NULL,
  `sourceid` int(11) DEFAULT NULL,
  `membertype` varchar(100) DEFAULT NULL,
  `itemid` varchar(100) DEFAULT NULL,
  `status` varchar(100) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `paymentconfirmation`
--

INSERT INTO `paymentconfirmation` (`paymentconfirmations_id`, `tracking_id`, `merchant_reference`, `paymentdate`, `amount`, `curid`, `paiditem`, `sourceid`, `membertype`, `itemid`, `status`) VALUES
(1, '7054f582-8f99-4c8d-b0c9-7f1d83111ebb', 52, 1410935974, '300', 1, 'course', 48, 'student', '52', 'COMPLETED');

-- --------------------------------------------------------

--
-- Table structure for table `prices`
--

CREATE TABLE IF NOT EXISTS `prices` (
  `priceid` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `curid` int(11) NOT NULL,
  `ptype` text NOT NULL,
  `courseid` int(11) DEFAULT NULL,
  `publicationid` int(11) DEFAULT NULL
) ENGINE=MyISAM AUTO_INCREMENT=37 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `prices`
--

INSERT INTO `prices` (`priceid`, `price`, `curid`, `ptype`, `courseid`, `publicationid`) VALUES
(11, '0.00', 1, 'course', 0, 0),
(12, '0.00', 1, 'course', 0, 0),
(13, '0.00', 1, 'course', 0, 0),
(14, '0.00', 1, 'course', 0, 0),
(15, '150.00', 1, 'course', 49, 0),
(16, '0.00', 1, 'course', 0, 0),
(17, '150.00', 1, 'course', 50, 0),
(18, '100.00', 1, 'course', 67, 0),
(19, '150.00', 1, 'course', 68, 0),
(20, '200.00', 1, 'course', 69, 0),
(21, '0.00', 1, 'course', 0, 0),
(22, '0.00', 1, 'course', 0, 0),
(23, '0.00', 1, 'course', 0, 0),
(24, '0.00', 1, 'course', 0, 0),
(25, '0.00', 1, 'course', 0, 0),
(26, '0.00', 1, 'course', 0, 0),
(27, '0.00', 1, 'course', 0, 0),
(28, '0.00', 1, 'course', 0, 0),
(29, '40000.00', 1, 'course', 70, 0),
(30, '150.00', 1, 'course', 53, 0),
(31, '100.00', 1, 'course', 73, 0),
(32, '100.00', 1, 'course', 74, 0),
(33, '100.00', 1, 'course', 76, 0),
(34, '100.00', 1, 'course', 51, 0),
(35, '0.00', 1, 'publication', 0, 1633),
(36, '300.00', 1, 'course', 52, 0);

-- --------------------------------------------------------

--
-- Table structure for table `publications`
--

CREATE TABLE IF NOT EXISTS `publications` (
  `publicationid` int(11) NOT NULL,
  `title` text NOT NULL,
  `body` longtext NOT NULL,
  `courseid` int(11) NOT NULL,
  `authorid` int(11) NOT NULL,
  `publishdate` int(11) NOT NULL,
  `ptype` varchar(100) NOT NULL,
  `docid` int(11) DEFAULT NULL
) ENGINE=MyISAM AUTO_INCREMENT=1643 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `publications`
--

INSERT INTO `publications` (`publicationid`, `title`, `body`, `courseid`, `authorid`, `publishdate`, `ptype`, `docid`) VALUES
(1641, 'Localising humanitarian response can help better meet the needs of crisis affected people', '<!DOCTYPE html>\r\n<html>\r\n<head>\r\n</head>\r\n<body>\r\n<div class="floatbox">\r\n<div class="pos-content">\r\n<div class="element element-textarea first last">\r\n<div>\r\n<p>Local organisations such as National Red Cross and Red Crescent Societies and national NGOs are among the first responders to disasters and outbreaks of violence.</p>\r\n</div>\r\n<div>\r\n<p>Kenya Red Cross, for example, was in the forefront of providing assistance to those critically injured in the al-Shabab attack on Nairobi&rsquo;s Westgate Mall in September 2013, and provided medical evacuations and psychosocial support to the survivors of the Garissa University attack earlier this year. Coming from among the affected population, local organisations provide built-in opportunities for humanitarian action that is not only timely but also highly relevant to the priorities of those affected. Local actors are there before, during and after the crisis and so can help foster coherence between humanitarian action and sustainable development. They are also there to respond to the smaller disasters that don&rsquo;t make the international headlines. Working with their counterparts in government, many of which now have increasingly professionalized disaster management bodies, local organisations can form part of nationally-led efforts to manage the risk and impact of disasters. &nbsp;For example, Kenya Red Cross works closely with the National Drought Management Authority to translate hazard early warning indicators into early response activities at the community level.While the role of local organisations has long been recognized in the major UN General Assembly Resolution on humanitarian assistance, the Code of Conduct for the Red Crescent Movement and NGOs in Disaster Relief and the principles of Good Humanitarian Donorship, donors and international agencies have been slow in taking steps to make this rhetoric a reality.</p>\r\n<p>&nbsp;</p>\r\n<p>A new paper from the Humanitarian Policy Group, commissioned by the British Red Cross and the International Federation of Red Cross and Red Crescent Societies, argues that national and local actors have been kept at arm&rsquo;s length by the international humanitarian community. National NGOs, for instance, receive only a tiny portion &ndash; estimated at 1.2 per cent &ndash; of international humanitarian funding. Local organisations have immense potential to help meet the needs of people affected by conflict and disasters. This is immediately clear when looking at some of the most high-profile crises of the last year. The work of the National Red Cross Societies of Guinea, Liberia and Sierra Leone, facilitating safe and dignified burials for victims, has been critical in bringing Ebola under control in West Africa. Whilst, amidst extreme insecurity, the volunteers of the Syrian Arab Red Crescent have played a leading role in responding to the needs of those affected by the conflict. This is not to suggest that international humanitarian agencies do not have a crucial role to play. Local, national and international actors each offer comparative advantages that apply to varying extents in different contexts and crises. Indeed, better meeting the needs of those affected by humanitarian crises may be more a question of collaborative advantage &ndash; the gains of working effectively in partnership &ndash; than competition over who is best placed to deliver assistance. A more inclusive and complementary approach to the funding and delivery of humanitarian aid, however, brings with it a number of challenges that will have to be grappled with in the run up to the World Humanitarian Summit.</p>\r\n<p>&nbsp;</p>\r\n<p>First, with humanitarian funding growing by over 1,000 per cent in the last 14 years, donors are keen to write bigger cheques to a smaller number of large agencies. Honest conversations and innovative thinking are needed on how to overcome this understandable risk-aversion, paving the way for gradual increases in direct funding for national and local actors, and helping to avoid wasteful subcontracting arrangements. To be sustainable, however, local organisations also need to raise funds locally. The Kenyans for Kenya initiative, which brought in over USD 8.5m in response to the 2011 drought emergency, is an example ripe for replication. Second, the increasing shift to local organisations in challenging environments from Syria to West Africa, can, at times, expose staff and volunteers to unmitigated risk. Support for strengthening safety and security management among local actors should therefore be a matter of urgency. Finally, greater emphasis on the merits of local organisations should not be seen to undermine the mandate and work of international actors. The presence and proximity of international agencies to affected populations remains critical to their protection and to effective assistance, particularly in today&rsquo;s most sensitive conflict situations.</p>\r\n<p>&nbsp;</p>\r\n<p>Responsibility for building a common way forward on these challenges lies with all of us involved &ndash; the international community, affected states and local organisations.</p>\r\n<p>&nbsp;</p>\r\n<p>N.B. This blog has been written by Dr. James Kisia and Mr. Samuel Carpenter, Policy Adviser at British Red Cross and a member of the World Humanitarian Summit Reducing Vulnerability and Managing Risk Thematic Team.</p>\r\n</div>\r\n</div>\r\n</div>\r\n</div>\r\n</body>\r\n</html>', 0, 2, 1443110047, 'publication', NULL),
(1642, 'Localising humanitarian response can help better meet the needs of crisis affected people', '<!DOCTYPE html>\r\n<html>\r\n<head>\r\n</head>\r\n<body>\r\n<div>\r\n<p>Local organisations such as National Red Cross and Red Crescent Societies and national NGOs are among the first responders to disasters and outbreaks of violence.</p>\r\n</div>\r\n<p>Kenya Red Cross, for example, was in the forefront of providing assistance to those critically injured in the al-Shabab attack on Nairobi&rsquo;s Westgate Mall in September 2013, and provided medical evacuations and psychosocial support to the survivors of the Garissa University attack earlier this year.</p>\r\n</body>\r\n</html>', 0, 2, 1443189043, 'researchpaper', 979);

-- --------------------------------------------------------

--
-- Table structure for table `region`
--

CREATE TABLE IF NOT EXISTS `region` (
  `regionid` int(11) NOT NULL,
  `region` text NOT NULL,
  `parentregionid` int(11) NOT NULL,
  `enabled` text NOT NULL
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `region`
--

INSERT INTO `region` (`regionid`, `region`, `parentregionid`, `enabled`) VALUES
(1, 'Africa', 0, 'yes'),
(2, 'Europe', 0, 'yes'),
(5, 'Latin America', 0, 'yes'),
(6, 'Asia', 0, 'yes');

-- --------------------------------------------------------

--
-- Table structure for table `sbusers`
--

CREATE TABLE IF NOT EXISTS `sbusers` (
  `sbid` int(11) NOT NULL,
  `fullname` varchar(200) NOT NULL,
  `email` varchar(200) NOT NULL,
  `enabled` text NOT NULL,
  `otherdetails` text
) ENGINE=MyISAM AUTO_INCREMENT=13 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `sbusers`
--

INSERT INTO `sbusers` (`sbid`, `fullname`, `email`, `enabled`, `otherdetails`) VALUES
(4, 'Antony Kaguimah', 'kaguimah@gmail.com', 'yes', NULL),
(5, 'Ska Ngumo', 'sngumo@gmail.com', 'yes', NULL),
(6, 'ICHA Administrator', 'info@icha.net', 'yes', NULL),
(9, 'ICHA Admin', 'info@icha.net', 'yes', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE IF NOT EXISTS `sessions` (
  `sessionid` varchar(200) NOT NULL,
  `username` varchar(150) NOT NULL,
  `usertype` text NOT NULL,
  `timestamp` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`sessionid`, `username`, `usertype`, `timestamp`) VALUES
('0d616ed4fd4b0cd185bf7561ffd5b967', 'administrator', 'adm', 1418806471);

-- --------------------------------------------------------

--
-- Table structure for table `session_data`
--

CREATE TABLE IF NOT EXISTS `session_data` (
  `session_id` varchar(100) NOT NULL,
  `hash` varchar(100) NOT NULL,
  `session_data` longtext NOT NULL,
  `session_expire` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `session_data`
--

INSERT INTO `session_data` (`session_id`, `hash`, `session_data`, `session_expire`) VALUES
('1gpqk9j4seot5iq7casauu47l1', '9e6508832c3b25a5cb66f48d0b641d90', 'zebra_csrf_token_loginform|a:2:{i:0;s:32:"15be42b260e4496aba795b19d7d97910";i:1;i:0;}', 1439908121),
('4qgrdbe42tg59qb8rc6ni3mio4', '9e6508832c3b25a5cb66f48d0b641d90', 'zebra_csrf_token_loginform|a:2:{i:0;s:32:"5cd711d5699a911318aa9789c56f5aea";i:1;i:0;}', 1439890555),
('7bddm6s07n1m9m7j5jrhf2qln1', '9e6508832c3b25a5cb66f48d0b641d90', 'zebra_csrf_token_loginform|a:2:{i:0;s:32:"b4ef8393687919f41d93a8bd484e3eb2";i:1;i:0;}', 1439896786),
('9hkvr39t36ubrfqoil6ldsh4l3', '9e6508832c3b25a5cb66f48d0b641d90', 'zebra_csrf_token_loginform|a:2:{i:0;s:32:"8a3e4ee011edcf8f8fdde1689db1bfe4";i:1;i:0;}', 1439830642),
('cra12nq6utniksqqa97fop15c2', '9e6508832c3b25a5cb66f48d0b641d90', 'zebra_csrf_token_loginform|a:2:{i:0;s:32:"4ec758e5cca7f124c5a46224ef54f62d";i:1;i:0;}', 1439661427),
('dsvsvr6sb7pr0hgbrm69k7ir46', '9e6508832c3b25a5cb66f48d0b641d90', 'zebra_csrf_token_loginform|a:2:{i:0;s:32:"92075c34f5881c5172428b9e0a4c3564";i:1;i:0;}', 1439903034),
('gistb517l1nqnfslhtv0h90he5', '9e6508832c3b25a5cb66f48d0b641d90', 'zebra_csrf_token_loginform|a:2:{i:0;s:32:"6a6f0d6a46fd065bfe8077f089487865";i:1;i:0;}', 1439975005),
('krqepvid61njroolqkmjrahgu2', '9e6508832c3b25a5cb66f48d0b641d90', 'zebra_csrf_token_loginform|a:2:{i:0;s:32:"04daf1ed36dc0ecd33b31bc47c9f63bb";i:1;i:0;}', 1440603778),
('lnhbh7832s45vmr36rsd0dhq47', '9e6508832c3b25a5cb66f48d0b641d90', 'zebra_csrf_token_loginform|a:2:{i:0;s:32:"2e6318efd9fca200a3489b4020a24bc2";i:1;i:0;}', 1439653584),
('q5b932p21gid9f2qaqe8gv86o5', '9e6508832c3b25a5cb66f48d0b641d90', '', 1439905465),
('rcoloacbthqb1c8csmvl2kg6n0', '9e6508832c3b25a5cb66f48d0b641d90', 'zebra_csrf_token_loginform|a:2:{i:0;s:32:"477c1e05856d37af3f4e4cdd31b21730";i:1;i:0;}', 1439665071);

-- --------------------------------------------------------

--
-- Table structure for table `shoppingcart`
--

CREATE TABLE IF NOT EXISTS `shoppingcart` (
  `cartid` int(11) NOT NULL,
  `shopperid` int(11) NOT NULL,
  `shoppertype` varchar(100) NOT NULL,
  `sessionid` text NOT NULL,
  `productid` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `currencyid` int(11) NOT NULL,
  `datetime` int(11) NOT NULL
) ENGINE=MyISAM AUTO_INCREMENT=149 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `shoppingcart`
--

INSERT INTO `shoppingcart` (`cartid`, `shopperid`, `shoppertype`, `sessionid`, `productid`, `price`, `currencyid`, `datetime`) VALUES
(147, 16, 'guest', '3f9ef0bc319dcc030c71e51637cbf716', 1638, '100.00', 1, 1398509604),
(148, 42, 'sub', '3f9ef0bc319dcc030c71e51637cbf716', 1640, '0.00', 1, 1398510050);

-- --------------------------------------------------------

--
-- Table structure for table `siteactivity`
--

CREATE TABLE IF NOT EXISTS `siteactivity` (
  `activityid` int(11) NOT NULL,
  `articleid` int(11) NOT NULL,
  `hits` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `students`
--

CREATE TABLE IF NOT EXISTS `students` (
  `students_id` int(11) NOT NULL,
  `registrationid` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `name` text COLLATE utf8_unicode_ci NOT NULL,
  `idno` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `dateofbirth` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `emailaddress` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `mobile` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `studymode` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `approved` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `registrationdate` int(11) DEFAULT NULL,
  `foldername` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `filename` varchar(150) COLLATE utf8_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8_unicode_ci
) ENGINE=InnoDB AUTO_INCREMENT=50 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `students`
--

INSERT INTO `students` (`students_id`, `registrationid`, `name`, `idno`, `dateofbirth`, `emailaddress`, `mobile`, `studymode`, `approved`, `registrationdate`, `foldername`, `filename`, `description`) VALUES
(1, 'ICHA/00050/2015', 'anthony kaguimah', '11212343', '952322400', 'anthony.kamau@hotmail.com', '234788', 'Online', 'no', 1443415438, NULL, NULL, NULL),
(45, 'ICHA/00045/2014', 'Stanley Ngumo', '22905055', '435387600', 'sngumo@gmail.com', '0722958720', 'Online', 'Yes', 1410255717, 'stanleyn', 'graduated.png', NULL),
(49, 'ICHA/00046/2015', 'Stanley Ngumo', '22905500', '842911200', 'sngumo@gmail2.com', '0722954748', 'Online', 'no', 1443178465, NULL, 'user_icon.png', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `students_courses_prices`
--

CREATE TABLE IF NOT EXISTS `students_courses_prices` (
  `students_courses_prices_id` int(11) NOT NULL,
  `students_id` int(11) NOT NULL,
  `courseid` int(11) NOT NULL,
  `priceid` int(11) NOT NULL,
  `paymentdate` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `students_documents`
--

CREATE TABLE IF NOT EXISTS `students_documents` (
  `students_documents_id` int(11) NOT NULL,
  `students_id` int(11) DEFAULT NULL,
  `documents_id` int(11) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=38 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `students_documents`
--

INSERT INTO `students_documents` (`students_documents_id`, `students_id`, `documents_id`) VALUES
(1, 0, 980),
(3, 25, 940),
(4, 25, 937),
(5, 25, 939),
(8, 36, 944),
(9, 36, 945),
(14, 40, 0),
(15, 40, 14),
(20, 50, 961),
(21, 50, 962),
(22, 51, 963),
(23, 51, 964),
(24, 43, 966),
(25, 43, 967),
(26, 44, 968),
(27, 44, 969),
(28, 45, 970),
(29, 45, 971),
(30, 46, 972),
(31, 46, 973),
(32, 47, 974),
(33, 47, 975),
(34, 48, 976),
(35, 48, 977),
(36, 46, 0),
(37, 46, 0);

-- --------------------------------------------------------

--
-- Table structure for table `students_payment_trials`
--

CREATE TABLE IF NOT EXISTS `students_payment_trials` (
  `trialid` int(11) NOT NULL,
  `students_id` int(11) NOT NULL,
  `courseid` int(11) NOT NULL,
  `emailaddress` varchar(150) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `students_payment_trials`
--

INSERT INTO `students_payment_trials` (`trialid`, `students_id`, `courseid`, `emailaddress`) VALUES
(1, 45, 50, 'sngumo@gmail.com'),
(2, 46, 52, 'saramnjeru@yahoo.com'),
(3, 48, 52, 'saramnjeru@yahoo.com');

-- --------------------------------------------------------

--
-- Table structure for table `student_courses`
--

CREATE TABLE IF NOT EXISTS `student_courses` (
  `student_courses_id` int(11) NOT NULL,
  `students_id` int(11) DEFAULT NULL,
  `courseid` int(11) DEFAULT NULL
) ENGINE=MyISAM AUTO_INCREMENT=48 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `student_courses`
--

INSERT INTO `student_courses` (`student_courses_id`, `students_id`, `courseid`) VALUES
(46, 49, 95),
(45, 48, 95),
(44, 47, 95),
(43, 46, 95),
(47, 0, 98);

-- --------------------------------------------------------

--
-- Table structure for table `student_tests_assignments`
--

CREATE TABLE IF NOT EXISTS `student_tests_assignments` (
  `student_tests_assignments_id` int(11) NOT NULL,
  `students_id` int(11) DEFAULT NULL,
  `tests_assignments_id` int(11) DEFAULT NULL,
  `downloaddate` int(11) DEFAULT NULL,
  `uploaddate` int(11) DEFAULT NULL,
  `students_documents_id` int(11) DEFAULT NULL,
  `marked_document_id` int(11) NOT NULL,
  `grade` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Triggers `student_tests_assignments`
--
DELIMITER $$
CREATE TRIGGER `after_student_tests_delete` AFTER DELETE ON `student_tests_assignments`
 FOR EACH ROW BEGIN
    DELETE FROM students_documents WHERE students_documents.students_documents_id = OLD.students_documents_id;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `subscribers`
--

CREATE TABLE IF NOT EXISTS `subscribers` (
  `subid` int(11) NOT NULL,
  `name` text NOT NULL,
  `mobileno` text NOT NULL,
  `email` text NOT NULL,
  `enabled` text NOT NULL,
  `regdate` int(11) NOT NULL
) ENGINE=MyISAM AUTO_INCREMENT=45 DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tests_assignments`
--

CREATE TABLE IF NOT EXISTS `tests_assignments` (
  `tests_assignments_id` int(11) NOT NULL,
  `document_id` int(11) DEFAULT NULL,
  `creationdate` int(11) DEFAULT NULL,
  `duedate` int(11) NOT NULL,
  `name` varchar(400) COLLATE utf8_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8_unicode_ci NOT NULL,
  `instructor_id` int(11) DEFAULT NULL,
  `courseid` int(11) NOT NULL,
  `tatype` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Triggers `tests_assignments`
--
DELIMITER $$
CREATE TRIGGER `after_test_assignments_delete` AFTER DELETE ON `tests_assignments`
 FOR EACH ROW BEGIN
    
    DELETE FROM documents WHERE documents.docid = OLD.document_id;

END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `userid` int(11) NOT NULL,
  `username` varchar(200) NOT NULL,
  `password` varchar(300) NOT NULL,
  `sourceid` int(11) NOT NULL,
  `enabled` text NOT NULL,
  `accesslevelid` int(11) NOT NULL,
  `lastlogin` int(11) DEFAULT NULL,
  `usertype` varchar(100) DEFAULT NULL
) ENGINE=MyISAM AUTO_INCREMENT=82 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`userid`, `username`, `password`, `sourceid`, `enabled`, `accesslevelid`, `lastlogin`, `usertype`) VALUES
(49, 'antony', '123456', 4, 'yes', 9, NULL, 'subscriber'),
(53, 'dan', 'juma', 6, 'yes', 9, 1435141664, 'instructor'),
(50, 'admin', 'icha2015', 5, 'yes', 8, 1445242905, 'admin'),
(51, 'stanley', 'muchiri', 6, 'yes', 8, 1439287936, 'admin'),
(54, 'sngumo@nerosolutions.com', 'tc8qdb8g', 40, 'yes', 9, NULL, 'subscriber'),
(55, 'administrator', 'ichaadmin', 9, 'yes', 8, 1415279901, 'admin'),
(59, 'instructor', 'teacher', 11, 'yes', 15, 1435218609, 'instructor'),
(68, 'chizi@nerosolutions.com', '5rcv8oeh', 43, 'yes', 16, 1398530905, NULL),
(71, 'sngumo@test.com', 'w28igpe8', 44, 'yes', 16, 1399310327, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `accesslevels`
--
ALTER TABLE `accesslevels`
  ADD PRIMARY KEY (`accessid`);

--
-- Indexes for table `articles`
--
ALTER TABLE `articles`
  ADD PRIMARY KEY (`articleid`);

--
-- Indexes for table `bulletins`
--
ALTER TABLE `bulletins`
  ADD PRIMARY KEY (`bulletinid`);

--
-- Indexes for table `carthistory`
--
ALTER TABLE `carthistory`
  ADD PRIMARY KEY (`historyid`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`categoryid`);

--
-- Indexes for table `contributors`
--
ALTER TABLE `contributors`
  ADD PRIMARY KEY (`contributorid`);

--
-- Indexes for table `courses`
--
ALTER TABLE `courses`
  ADD PRIMARY KEY (`courseid`);

--
-- Indexes for table `currency`
--
ALTER TABLE `currency`
  ADD PRIMARY KEY (`currencyid`);

--
-- Indexes for table `documents`
--
ALTER TABLE `documents`
  ADD PRIMARY KEY (`docid`);

--
-- Indexes for table `events`
--
ALTER TABLE `events`
  ADD PRIMARY KEY (`eventid`);

--
-- Indexes for table `focusareas`
--
ALTER TABLE `focusareas`
  ADD PRIMARY KEY (`areaid`);

--
-- Indexes for table `frontpage`
--
ALTER TABLE `frontpage`
  ADD PRIMARY KEY (`frontid`);

--
-- Indexes for table `guest`
--
ALTER TABLE `guest`
  ADD PRIMARY KEY (`guestid`);

--
-- Indexes for table `imgmanager`
--
ALTER TABLE `imgmanager`
  ADD PRIMARY KEY (`imgid`);

--
-- Indexes for table `instructor_course`
--
ALTER TABLE `instructor_course`
  ADD PRIMARY KEY (`instructor_course_id`);

--
-- Indexes for table `menugroups`
--
ALTER TABLE `menugroups`
  ADD PRIMARY KEY (`groupid`), ADD KEY `groupid` (`groupid`);

--
-- Indexes for table `menus`
--
ALTER TABLE `menus`
  ADD PRIMARY KEY (`menuid`);

--
-- Indexes for table `paymentconfirmation`
--
ALTER TABLE `paymentconfirmation`
  ADD PRIMARY KEY (`paymentconfirmations_id`);

--
-- Indexes for table `prices`
--
ALTER TABLE `prices`
  ADD PRIMARY KEY (`priceid`);

--
-- Indexes for table `publications`
--
ALTER TABLE `publications`
  ADD PRIMARY KEY (`publicationid`);

--
-- Indexes for table `region`
--
ALTER TABLE `region`
  ADD PRIMARY KEY (`regionid`);

--
-- Indexes for table `sbusers`
--
ALTER TABLE `sbusers`
  ADD PRIMARY KEY (`sbid`);

--
-- Indexes for table `session_data`
--
ALTER TABLE `session_data`
  ADD PRIMARY KEY (`session_id`);

--
-- Indexes for table `shoppingcart`
--
ALTER TABLE `shoppingcart`
  ADD PRIMARY KEY (`cartid`);

--
-- Indexes for table `siteactivity`
--
ALTER TABLE `siteactivity`
  ADD PRIMARY KEY (`activityid`);

--
-- Indexes for table `students`
--
ALTER TABLE `students`
  ADD PRIMARY KEY (`students_id`), ADD UNIQUE KEY `registrationid` (`registrationid`);

--
-- Indexes for table `students_courses_prices`
--
ALTER TABLE `students_courses_prices`
  ADD PRIMARY KEY (`students_courses_prices_id`);

--
-- Indexes for table `students_documents`
--
ALTER TABLE `students_documents`
  ADD PRIMARY KEY (`students_documents_id`);

--
-- Indexes for table `students_payment_trials`
--
ALTER TABLE `students_payment_trials`
  ADD PRIMARY KEY (`trialid`);

--
-- Indexes for table `student_courses`
--
ALTER TABLE `student_courses`
  ADD PRIMARY KEY (`student_courses_id`);

--
-- Indexes for table `student_tests_assignments`
--
ALTER TABLE `student_tests_assignments`
  ADD PRIMARY KEY (`student_tests_assignments_id`);

--
-- Indexes for table `subscribers`
--
ALTER TABLE `subscribers`
  ADD PRIMARY KEY (`subid`);

--
-- Indexes for table `tests_assignments`
--
ALTER TABLE `tests_assignments`
  ADD PRIMARY KEY (`tests_assignments_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`userid`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `accesslevels`
--
ALTER TABLE `accesslevels`
  MODIFY `accessid` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=17;
--
-- AUTO_INCREMENT for table `articles`
--
ALTER TABLE `articles`
  MODIFY `articleid` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=1128;
--
-- AUTO_INCREMENT for table `bulletins`
--
ALTER TABLE `bulletins`
  MODIFY `bulletinid` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=19;
--
-- AUTO_INCREMENT for table `carthistory`
--
ALTER TABLE `carthistory`
  MODIFY `historyid` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=56;
--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `categoryid` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=9;
--
-- AUTO_INCREMENT for table `contributors`
--
ALTER TABLE `contributors`
  MODIFY `contributorid` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=14;
--
-- AUTO_INCREMENT for table `courses`
--
ALTER TABLE `courses`
  MODIFY `courseid` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=117;
--
-- AUTO_INCREMENT for table `currency`
--
ALTER TABLE `currency`
  MODIFY `currencyid` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `documents`
--
ALTER TABLE `documents`
  MODIFY `docid` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=982;
--
-- AUTO_INCREMENT for table `events`
--
ALTER TABLE `events`
  MODIFY `eventid` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT for table `focusareas`
--
ALTER TABLE `focusareas`
  MODIFY `areaid` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `frontpage`
--
ALTER TABLE `frontpage`
  MODIFY `frontid` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `guest`
--
ALTER TABLE `guest`
  MODIFY `guestid` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=17;
--
-- AUTO_INCREMENT for table `imgmanager`
--
ALTER TABLE `imgmanager`
  MODIFY `imgid` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=264;
--
-- AUTO_INCREMENT for table `instructor_course`
--
ALTER TABLE `instructor_course`
  MODIFY `instructor_course_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `menugroups`
--
ALTER TABLE `menugroups`
  MODIFY `groupid` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=9;
--
-- AUTO_INCREMENT for table `menus`
--
ALTER TABLE `menus`
  MODIFY `menuid` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=107;
--
-- AUTO_INCREMENT for table `paymentconfirmation`
--
ALTER TABLE `paymentconfirmation`
  MODIFY `paymentconfirmations_id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `prices`
--
ALTER TABLE `prices`
  MODIFY `priceid` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=37;
--
-- AUTO_INCREMENT for table `publications`
--
ALTER TABLE `publications`
  MODIFY `publicationid` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=1643;
--
-- AUTO_INCREMENT for table `region`
--
ALTER TABLE `region`
  MODIFY `regionid` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT for table `sbusers`
--
ALTER TABLE `sbusers`
  MODIFY `sbid` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=13;
--
-- AUTO_INCREMENT for table `shoppingcart`
--
ALTER TABLE `shoppingcart`
  MODIFY `cartid` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=149;
--
-- AUTO_INCREMENT for table `siteactivity`
--
ALTER TABLE `siteactivity`
  MODIFY `activityid` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `students`
--
ALTER TABLE `students`
  MODIFY `students_id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=50;
--
-- AUTO_INCREMENT for table `students_courses_prices`
--
ALTER TABLE `students_courses_prices`
  MODIFY `students_courses_prices_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `students_documents`
--
ALTER TABLE `students_documents`
  MODIFY `students_documents_id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=38;
--
-- AUTO_INCREMENT for table `students_payment_trials`
--
ALTER TABLE `students_payment_trials`
  MODIFY `trialid` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `student_courses`
--
ALTER TABLE `student_courses`
  MODIFY `student_courses_id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=48;
--
-- AUTO_INCREMENT for table `student_tests_assignments`
--
ALTER TABLE `student_tests_assignments`
  MODIFY `student_tests_assignments_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `subscribers`
--
ALTER TABLE `subscribers`
  MODIFY `subid` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=45;
--
-- AUTO_INCREMENT for table `tests_assignments`
--
ALTER TABLE `tests_assignments`
  MODIFY `tests_assignments_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `userid` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=82;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

-- phpMyAdmin SQL Dump
-- version 3.5.7
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Oct 29, 2013 at 09:41 AM
-- Server version: 5.5.29
-- PHP Version: 5.4.10

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `essay`
--

-- --------------------------------------------------------

--
-- Table structure for table `action`
--

CREATE TABLE `action` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `usr_id` int(11) NOT NULL DEFAULT '0',
  `essay_id` int(11) NOT NULL,
  `draft_id` int(11) NOT NULL,
  `submit` tinyint(4) NOT NULL,
  `active` tinyint(4) NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `usr_id` (`usr_id`),
  KEY `asg_id` (`essay_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=20 ;

--
-- Dumping data for table `action`
--

INSERT INTO `action` (`id`, `usr_id`, `essay_id`, `draft_id`, `submit`, `active`, `date`) VALUES
(1, 1, 0, 0, 0, 0, '2013-10-21 05:34:57'),
(2, 3, 0, 0, 0, 0, '2013-10-21 07:24:19'),
(3, 4, 0, 0, 0, 0, '2013-10-21 11:09:35'),
(4, 1, 1, 1, 1, 0, '2013-10-21 11:22:02'),
(5, 1, 2, 2, 1, 0, '2013-10-21 11:22:02'),
(6, 3, 3, 0, 0, 0, '2013-10-21 11:22:02'),
(7, 3, 4, 0, 0, 0, '2013-10-21 11:22:02'),
(8, 4, 5, 0, 0, 0, '2013-10-21 11:22:02'),
(9, 4, 6, 0, 0, 0, '2013-10-21 11:22:02'),
(10, 4, 7, 0, 0, 0, '2013-10-21 11:22:02'),
(11, 5, 0, 0, 0, 0, '2013-10-22 04:18:22'),
(14, 1, 8, 3, 1, 0, '2013-10-22 05:33:12'),
(15, 3, 9, 0, 0, 0, '2013-10-22 05:33:12'),
(16, 1, 10, 0, 0, 0, '2013-10-22 05:37:18'),
(17, 3, 11, 0, 0, 0, '2013-10-22 05:37:18'),
(18, 4, 12, 0, 0, 0, '2013-10-22 05:37:18'),
(19, 5, 13, 0, 0, 0, '2013-10-22 05:37:18');

-- --------------------------------------------------------

--
-- Table structure for table `cou`
--

CREATE TABLE `cou` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `usr_id` int(11) NOT NULL,
  `update_count` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

--
-- Dumping data for table `cou`
--

INSERT INTO `cou` (`id`, `usr_id`, `update_count`) VALUES
(1, 1, 1),
(2, 3, 1),
(3, 4, 1),
(4, 5, 1);

-- --------------------------------------------------------

--
-- Table structure for table `essay`
--

CREATE TABLE `essay` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `prompt` text COLLATE utf8_unicode_ci NOT NULL,
  `essay` text COLLATE utf8_unicode_ci NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `chk` varchar(2) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'N',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=14 ;

--
-- Dumping data for table `essay`
--

INSERT INTO `essay` (`id`, `prompt`, `essay`, `date`, `chk`) VALUES
(0, '', '', '0000-00-00 00:00:00', 'N'),
(1, 'Some people say that physical exercise should be a required part of every school day. Other people believe that students should spend the whole school day on academic studies. Which opinion do you agree with? Use specific reasons and details to support your answer.', '<IN>Many school students must study their text books and tests during the class time. When I was a school student in my country, many students were studying a lot. Some friends were playing their exercise for much great health if they had a time. They were always healthy body, and they never had any disease because of their good health. <TS>I think that physical exercise should be a required part of every school day for many students’ health.</TS></IN>\n \n<BO1><MI1>First reason is that physical exercise can help to make a good studying for many students.</MI1> <SI1>Like my friends’ great health, the good health can make good body. <TR>Also</TR>, it can make a good study for many students, because good health body can help to concentrate studying when students try to study themselves. If they don’t have a good health, the terrible disease will bother you. <TR>So</TR>, if school have a physical exercise everyday, it will be better.</SI1></BO1> \n\n<BO2><MI2>Another reason is that physical exercise can help to make a successful life for many students.</MI2> <SI2><EX><TR>For example</TR>, many students, who have the best grade in the world with a good health, have a good chance to make their dreams. Their ability can join with a great job like a U.S air force, NASA member, or pilot.</EX> These jobs are very popular for many people. If I have the best grade and great health, these job isn’t difficult for me.</SI2></BO2>\n\n<CO><TR>In conclusion</TR>, requiring physical exercise can make many students’ studying and successful life. Without this in school, maybe some students will have a trouble with disease and weak body which are reducing concentration and hard studying for them. <TR>So</TR>, If school admit this rule, many student will have a great job for their dream.</CO>', '0000-00-00 00:00:00', 'Y'),
(2, 'Do you agree or disagree with the following statement? Reading fiction (such as novels and short stories) is more enjoyable than watching movies. Use specific reasons and examples to explain your position.', '<IN><TR>Long time ago</TR>, many people thought reading books like short stories or novels are enjoyable things. <TR>But today</TR>, this sentence is just old saying, because many people think that the developing of television and screen are more enjoyable than books. <TS><TR>So</TR>, I think I disagree with this following statement.</TS> I have some reasons to explain about my choice which are the screen action and easy to understand story.</IN>\r\n \r\n<BO1><MI1>The first reason is watching movie is more enjoyable than reading books because of screen action of the movie.</MI1> <SI1>If many people watch the movie in theater, they can memorize about all of story. Watching screen is very easier to memorize story than reading books for people. Even though reading book is very good for many people to making imagination or creation, it doesn’t help to memorize the whole story of book correctly.</SI1><BO1>\r\n\r\n<BO2><MI2><TR>Furthermore</TR>, watching movie is making fun to many people who are watching.</MI2> <SI2>Reading books also make fun to readers, but this is not easy because for finding funny part, many readers must imagine the story from their mind. Imaging the story needs more time to understand about funny parts. So I think watching movie is very convenient for many people because movie can make fun very easily by its screen.</SI2></BO2>\r\n\r\n<CO><TR>In conclusion</TR>, many people said that watching movie is more enjoyable than reading books because of its understanding story very easily and correctly. <TR>So</TR>, many people want to watch the movie instead of reading books today. After 100 years later, the book will be useless for many people I think because of useful and fun watching movie.</CO>', '0000-00-00 00:00:00', 'Y'),
(3, 'Do you agree or disagree with the following statement? Television has destroyed communication among friends and family. Use specific reasons and examples to support your opinion', '<IN><TR>Now</TR>, almost every house has more than one television. There are too many channels and many TV programs give us chances to laugh and take a rest. Television has both advantages and disadvantages. <TS>For disadvantages of TV, some people say that Television has destroyed communication among friends and family. I agree this statement since dialog between family members and friends has disappeared and people have difficulty with finding common things to talk together because of television. </TS></IN>\r\n\r\n<BO1><MI1>Television has decreased the amount of time for people to talk with family members and friends.</MI1> <SI1><EX><TR>For example</TR>, every family member watches television without talking each other even though they are in the same place &#8211; living room. After watching TV without any words each other, some of them will go to the room and the others will continue to watch another TV programs.</EX> They do not talk with other family members since all of them watches television. In orde to communicate with other people, we have to make considerable efforts and have to give up watching television. <TR>However</TR>, it is not so easy and many people are accustomed to watch television passively. <TR>In this respect</TR>, it seems certain that television has prohibited communication between people.</SI1></BO1>\r\n\r\n<BO2><MI2>As almost every person watches television everyday, people who do not watch television can not communicate with other people since there hardly exist something to talk with in common.</MI2> <SI2><EX><TR>For example</TR>, a person who do not watch ‘X-file’ can not communicate with his/her friends who will talk about Mulder , a hero of the program</EX>. <TR>In other word</TR>, television destroys communication between friends by narrowing people’s interests into TV programs.</SI2></BO2>\r\n\r\n<CO><TR>Admittedly</TR>, television might have helped interpersonal communications but those effects are small and negligible with respect to the problems it has cause. <TR>Instead</TR>, television seems to have destroyed interpersonal communications by taking time for family members and friends to talk each other. Moreover it has made talking topics of people much narrower gradually by making people think about what they have watched from television yesterday.</CO>', '0000-00-00 00:00:00', 'Y'),
(4, 'How do movies or television influence people''s behavior? Use reasons and specific examples to support your answer.', '<IN><TR>Generally speaking</TR>, movies and television affect the people''s behavior. <TS>The more the technologies develop, the more we are exposed to the mass communication, therefore we can''t explain our lives without TV and movies.</TS> It seems clear that Movies and televisions have both advantages and disadvantages.</IN>\r\n\r\n<BO1><MI1><TR>Firstly</TR>, A special concern is whether movies and television make children and society more violent.</MI1> <SI1>When watching TV, sometimes I see violent and terrible scenes which is not filtered. <TR>As a result</TR>, this can cause a serious crime. In movies actors can be killed and come back for another movie. Sometimes we confuse that with real world. We have got to realize that killing someone cause a permanent consequence.</SI1></BO1> \r\n\r\n<BO2><MI2><TR>Secondly</TR>, Movies and television make us less active.</MI2> <SI2>When we watch TV, we are absorbed in the TV program without knowing time is passing by. <TR>Accordingly</TR>, we lost time for finishing another important tasks and exercising. That is one of the reasons why modern people are inferior to people 30 years ago in health. <TR>In this sense</TR>, by watching TV and movies too much, we become passive and unhealthier than now.</SI2><BO2>\r\n\r\n<BO3><MI3>Movies and television always don''t have disadvantages.</MI3> <SI3>Information from TV is very useful for us. When we go on a picnic, we often rely on the information from TV. That is why information from TV is more credible than anything else. Above all the best influence on our behavior is that movies and televisions reduce stress from hard working. Watching films or televisions, we can escape our own problems for a while.</SI3></BO3>\r\n\r\n<CO>Movies and television can influence on our behaviors for better or worse. <TR>To sum up</TR>, they can make us more violent, and people can be comfortable watching a Television show program or a film. <TR>However</TR>, movies and television can do good or do harm. That completely depends on you. If you watch movies and television wisely, you can give as much as advantage you want.</CO>', '0000-00-00 00:00:00', 'Y'),
(5, 'What do you consider to be the most important room in a house? Why is this room more important to you than any other room? Use specific reasons and examples to support your opinion.', '<IN><TR>Generally</TR>, a house consists of several rooms playing different roles such as a livingroom, a bedroom, a bathroom, etc. People''s opinions about what room is more important than any other room can be different depending on individuals. <TS><TR>As far as I am concerned</TR>, I believe that no room is as important as a livingroom for the following two reasons. <TR>First</TR>, a living room is used to entertain visitors. <TR>Second</TR>, it is a good place to relieve stress.</TS></IN>\r\n \r\n<BO1><MI1><TR>To begin with</TR>, people mostly welcome their visitors to a living room.</MI1> <SI1>The living room is on behalf of the house. <TR>Usually</TR>, visitors can easily notice not only the atmosphere of the house but also the landlord''s taste while staying in the livingroom. <TR>Consequently</TR>, the living room gives the first impression of the house to guests. This is why people should strive to maintain their livingrooms clean all the time. <EX><TR>For example</TR>, I dropped by one of my co-workers a couple of years ago, when his livingroom was considerably messy. I felt disordered atmosphere seeing a lot of magazines, some beer cans, and even trash thrown away all over the place, which made me feel uncomfortable.</EX> <TR>Since then</TR>, I found myself avoiding visiting his house.</SI1></BO1>\r\n\r\n<BO2><MI2>In addition to an integral role of a living room as mentioned above, while in a livingroom, it is possible for people to relax themselves.</MI2> <SI2><TR>That is to say</TR>, the livingroom provides them with a place to enjoy various amusements, which result in forgetting all stressful matters coming from daily routine life. When watching a series of comedies on TV in my livingroom, <TR>for instance</TR>, I could not be better than any other time. <TR>Also</TR>, I can relax myself while sitting on sofa and reading a kind of detective story. If there had been no livingroom in my house, it would have been probably difficult for me to lead this comfortable life.</SI2></BO2>\r\n\r\n<CO><TR>In conclusion</TR>, I am convinced that a livingroom is the most significant room in my house. It is because a livingroom is the place to alleviate stress effectively as well as entertain visitors. If people want to make a first good impression of their houses for their visitors, they should keep their livingrooms spotless and neat.</CO>', '0000-00-00 00:00:00', 'Y'),
(6, 'Your school has received a gift of money. What do you think is the best way for your school to spend this money? Use specific reasons and details to support your choice.', '<IN><TR>Generally</TR>, a school exist for students. If a school has received a gift of money, it is spent absolutely for students. <TS>If my school get a gift of money, I wish that my school spends the money to build a library and enlarge scholarship.</TS> I believe that such things make students try to study hard.</IN>\r\n\r\n<BO1><MI1>In my school''s library, some students go around to seek a empty seat.</MI1> <SI1><TR>Occasionally</TR>, they return to their home as they fail to seek a empty seat. <TR>Usually</TR>, many schools do not have enough seat to accomodate their students. So does my school. <TR>Thus</TR> I want for my school to build a spacious library. Library will provide students good mood and convenience to study.</SI1></BO1>\r\n\r\n<BO2><MI2><TR>Moreover</TR>, I hope that my school increases scholarship.</MI2> <SI2>The scholarship is not only very useful to poor student but also effective to make students study hard. <TR>So</TR>, if scholarship is expanded, many people who is poor but smart would apply to my school and plain students would plunge themselves into the competition to get a scholarship.</SI2></BO2>\r\n\r\n<CO><TR>In conclusion</TR>, I prefer to let my school build a library and enlarge scholarship through a gift of money. Those are good things for both my school and students. If my school do that, my school would be upgraded and that means that students would be done too.</CO>', '0000-00-00 00:00:00', 'Y'),
(7, 'Some people believe that university students should be requireed to attend class. Others believe that going to class should be optiomal for students. Which point of view do you agree with?', '<IN><TR>In korea</TR>, my country, a university has been regarded the place which is not only for learning skills, but also for gathering experiences of life. <TR>Therefore</TR>, It is hard to imagin a uinversity where students are not expected to attend classes. <TS><TR>In my point of view</TR>, I also think that students have to attend clssses.</TS> There are two reasons why I emphasize importance of classes.</IN>\r\n\r\n<BO1><MI1><TR>First of all</TR>, students could learn knowledges which they didnot know before through a discussion with their classmates and professors.</MI1> <SI1><EX></TR>For example</TR>, when I attended a university, I faced some unsolved questions.<TR>however</TR>, I could solve that questions through discussion with my classmates. <TR>Sometimes</TR>, your classmates could be your good tutor.</EX></SI1></BO1>\r\n\r\n<BO2><MI2><TR>Secondly</TR>, students could grow sociability which is very important for modern life.</MI2> <SI2>A university is miniature of society. attending classes could be useful to grow sociability. <TR>In my case</TR>, I was very self-conscious person before i went to university, but I could change myself by attending presentation and argumentation in the class. <TR>For this reason</TR>, students should attend classes.</SI2></BO2> \r\n\r\n<CO><TR>Although</TR>, <TR>nowadays</TR>, most people consider that a class is just method of training skills, students should not ignore the importance of attending classes because there are so many advantages besides obtnaining a piece of knowledge.</CO>', '0000-00-00 00:00:00', 'Y'),
(8, 'People attend college or university for many different reasons (for example, new experiences, career preparation, increased knowledge). Why do you think people attend college or university? Use specific reasons and examples to support your answer.', '<IN><TR>In these days</TR>, people attend college or university for a lot of reasons. those depend on their indivisual view. <TS><TR>But</TR>, <TR>In my case</TR>, I think that people want to attend college to learn something what they do easily or systemically, to get oppotunity for their future job,and to get to meet peer group who is student with same major.</TS></IN>\r\n\r\n<BO1><MI1><TR>First</TR>, many people attend college to learn something easily.</MI1> <SI1>If you study somthing what you intersted by youself, you may do very hardly. But professors who is specalist give you systemic information. If you have some question, you may ask them and you may use library in university to find answer.</SI1></BO1> \r\n\r\n<BO2><MI2><TR>Second</TR>, the career what graduated college can give more chance when you get a job.</MI2> <SI2>If you get the diploma fo high school, you can''t find the job what you desired. <EX><TR>For example</TR>, if I want to get registerd nursing job, I can''t submit my resume to hospital where I want to work. Because they want registered nurse who graduated university as employee.</EX></SI2></BO2>\r\n\r\n<BO3><MI3><TR>Third</TR>, When you attend college, you may chance to meet peer group who have same major or interesting something.</MI3> <SI3>Because there are a lot of clubs in the college. You can attend anywhere you intrested easily.</SI3></BO3>\r\n\r\n<CO><TR>In conclusion</TR>, I think people attend college to learn knowledge, career preparation for future job and to meet their peer group with same major.</CO>', '0000-00-00 00:00:00', 'Y'),
(9, 'Many student choose to attend schools or universities outside their home countries. Why do some students study abroad? Use specific reasons and details to explain your answer.', '<IN><TR>Nowadays</TR>, many student are trying and preparing to study abroad. Others are studying and complete study abroad. <TS>Why so many students choose to study abroad? The reason are as follows : learning in advanced situation, learn foreign language, and experiencing othercultures.</TS></IN>\r\n\r\n<BO1><MI1><TR>First</TR>, students study abroad to learn in advanced situation.</MI1> <SI1>Many student in Asia who want to learn art go to Europe to learn art in detail. It''s because Europe has its long history of art. Some student in engineering or science want to study in U.S because in U.S, engineering and natural science is very developed.</SI1></BO1>\r\n\r\n<BO2><MI2><TR>Second</TR>, student go to other country especially United States and Great Britain because they want to learn English.</MI2> <SI2>English is a global language. If someone know English, he/she can make a communication with other people in most countries. <TR>Additionally</TR> if you learn English very well you can get a better job and are easy to get a promotion in a your company.</SI2></BO2>\r\n\r\n<BO3><MI3><TR>Third</TR>, student in abroad can experience different culture.</MI3> <SI3>Different culture is very interesting to students. They can feel and learn other country''s culture which they can only watch in television if they are in their country. Students in foreign country meet different kinds of people. <EX>If I choose go study in U.S., I would meet American, Chinese, Europian and etc.</EX> And there is differnet kind of lifestyle in abroad. Student should live in the way in which native citizen live. It would be a very exciting experience to them.</SI3></BO3>\r\n\r\n<CO><TR>To summerize</TR>, some student choose to study abroad to study in better situation, to learn foreign language, and to experience culture.</CO>', '0000-00-00 00:00:00', 'Y'),
(10, 'Why do you think some people are attracted to dangerous sports or other dangerous activities? Use specific reasons and examples to support your answer.', '<IN><TR>Nowadays</TR>, we can see many people who enjoy dangerous sports such as diving sky diving and skate boaiding. People certainly know the sports or activities are so dangerous that they can kill them. <TR>However</TR>, here are some reasons that let people enjoy dangerous sports or activities contiuously.</IN>\r\n\r\n<BO1><MI1><TR>First</TR>, people are attracted to dangerous sports or other dangerous activities because of satisfaction gained frome playing the sports or activities.</MI1> <SI1>We can not feel joy of dangerou sports or activities unless We do not play them. <TR>Hoever</TR>, once we can have a chance of playing skydiving or skate boarding, we can feel much thill and speeds of the game.</SI1></BO1>\r\n\r\n<BO2><MI2><TR>Second</TR>, hope for a dynamic life would be the reason that people are attracted to dangerous sports or other dangerous activities.</MI2> <SI2>many people in our society as a students or workers live very monotonously nowdays because of schedule of them. <TR>Thus</TR>, people sometimes want to do special thing to avoid their routine lives, so they would find dangerous but exciting sports or activities and want to do them.</SI2></BO2>\r\n\r\n<BO3><MI3><TR>Third</TR>, people are attracted to dangerous sports or other dangerous activities to be distinctive from others.</MI3> <SI3>In society, people would want to be singular. People can hardly become original in their schools or work places because they must do studies or work in the way other people do. <TR>However</TR>, as people do dangerous but special sports or activities, they can get feeling of uniqueness from others.</SI3></BO3>\r\n\r\n<CO><TR>In short</TR>, people want to play dangerous sports or activities because of satisfaction of themselves, hope for dynamic life, and being distinctive from others. We often hear news that some people died playing dangerous sports or activities like sky diving or hangliding. <TR>However</TR>, people will not stop their playing.</CO>', '0000-00-00 00:00:00', 'Y'),
(11, 'The government has announced that it plans to build a new university. Some people think that your community would be a good place to locate the university. Compare the advantages and disadvantages of establishing a new university in your community. Use specific details in your discussion.', '<IN><TR>Sometimes</TR>, establishing a new building in my community can be beneficial because that has some advantages. <TR>However</TR>, some other people think that it''s not good due to other weak points. <TS>There are both sides of good and bad points of establishing a university in my location.</TS></IN>\r\n\r\n<BO1><MI1><TR>To begin with</TR>, establishing a new university is good because the community will cause the econimic developement and the knowledge power of the community.</MI1> <SI1>More students will enter the local university rather than going to the university far away to other area. <TR>Then</TR>, the community enhances the intelligent business. More educated students will work in the community by opening a new business or entering a big or small and medium enterprises in the location. That will bring the economic development of the community. If the community develope more, the acknowledgement of the community will be increase. <TR>Then</TR>, that also will cause more benefits. So establishing a new university has good advantages.</SI1></BO1>\r\n\r\n<BO2><MI2><TR>However</TR>, there also some disadvantages in building a new university such as a social pollution.</MI2> <SI2><TR>Namely</TR>, the community will be very crowd with many young people, pleasure haunts will be increased, and the community may loose its calmness. <EX><TR>For example</TR>, a new university was established in my hometown some years ago. The area was very calm and peaceful before. <TR>However</TR>, there are so many people in the area and a lot of pups have opened so far. <TR>So</TR>, so many people started to crowd. Even though the community has flourished because of that, it also has polluted by many people and wastes. So building a new university is not always good for the society.</EX></SI2></BO2>\r\n\r\n<CO><TR>Thus</TR>, when we establish a new university, we should consider that in which point the society puts the importance. <TR>So</TR>, if the community has really to develop soon, they need to establish a new university. <TR>On the other hand</TR>, if they want to keep the community''s clean and peaceful environment, they should not build it.</CO>', '0000-00-00 00:00:00', 'Y'),
(12, 'Some people prefer to live in a small town. Others prefer to live in a big city. Which place would you prefer to live in? Use specific reasons and details to support your answer.', '<IN>People live in many living places. Many people consider where they live a big city or a small country. <TS>I prefer living in the big city. Because diverse convenient the public transportation system and many and profitable the cultural places are in a big city.</TS></IN>\r\n\r\n<BO1><MI1><TR>First</TR>, public transportation system of the big city is convenient because we can many option.</MI1> <SI1><TR>In general</TR>, we move ourselves for meeting people or going to the company.&nbsp;&nbsp;According to destination people select vehicle. If people determined their destination, they decision it after they consider various situation. <TR>For example</TR>, I select vehicle after I consider timely, comfortable and economical situation when I go to a random place. <TR>Thus</TR>, I use proper it. <TR>However</TR>, if I lived in the big city, it may be limited because a small city have barely the public transportation system. <TR>Therefore</TR>, urban people expediently use the public transportation system.</SI1></BO1> \r\n\r\n<BO2><MI2><TR>Second</TR>, the large city have beneficial and many the cultural place such as theater, museum, park and so on.</MI2> <SI2>Urban any people can use sometimes them. Many cultural place offer intellecture, pleasure, a rest place and many things. If I want that I see history of our nation or a movie, I can go near a museum or a theater. <TR>However</TR>, the rural people easily can''t the places. Because the small city rarely have them if one of them city are not in small city, the rural people can''t see or difficultly may go a goal. <TR>As we can see</TR>, because of beneficial and many cultural spots people of the large city profit a lot of thing.</SI2></BO2>\r\n\r\n<CO><TR>For the reasons</TR>, I prefer the great city. Because it not only include the public transportation system, but also the manifold and serviceable cultural system. <TR>Hence</TR>, I prefer living in the great city.</CO>', '0000-00-00 00:00:00', 'Y'),
(13, 'In general, people are living longer now. Discuss the causes of this phenomenon. Use specific reasons and details to develop your essay.', '<IN><TR>These days</TR>, many people pay attention to life’s quality and quantity. They tend to do exercise of walk around for their health. <TR>A few days ago</TR>, I read an article on weekly magazine about today’s lifespan. It said that, “People are living longer now.” <TS>I think that the reasons many people are living longer than before are on the development of modern medical technology and well trained health practice.</TS></IN>\r\n\r\n<BO1><MI1><TR>Above all</TR>, modern medial technology provides extension of one’s lifespan.</MI1> <SI1>Several of doctors and nurses are training with a great amount of medical knowledge. <TR>Plus</TR>, the number of specialist is increasing.</SI1></BO1>\r\n\r\n<BO2><MI2><TR>Secondly</TR>, <TR>modern times</TR>, good circumstances and people’s excellent practice offer living longer.</MI2> <SI2>People eat for balanced diet and drink water that is treated well. <EX><TR>For instance</TR>, my grand mother has a habit that is having only non-chemical vegetable and drinking only boiled water. Now she is eighty eighteen years old.</EX></SI2></BO2>\r\n\r\n<CO><TR>In conclusion</TR>, <TR>today</TR>, the extension of one’s lifespan has unseeing secrets. Those are breakthroughs in medial technology and people’s excellent practice.</CO>', '0000-00-00 00:00:00', 'Y');

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `session_id` varchar(40) CHARACTER SET latin1 NOT NULL DEFAULT '0',
  `ip_address` varchar(16) CHARACTER SET latin1 NOT NULL DEFAULT '0',
  `user_agent` varchar(120) CHARACTER SET latin1 NOT NULL,
  `last_activity` int(10) unsigned NOT NULL DEFAULT '0',
  `user_data` text CHARACTER SET latin1 NOT NULL,
  PRIMARY KEY (`session_id`),
  KEY `last_activity_idx` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`session_id`, `ip_address`, `user_agent`, `last_activity`, `user_data`) VALUES
('9e93284fe72368a5832007234409ed9b', '::1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_8_5) AppleWebKit/536.30.1 (KHTML, like Gecko) Version/6.0.5 Safari/536.30.1', 1383033608, 'a:5:{s:9:"user_data";s:0:"";s:8:"is_login";b:1;s:8:"nickname";s:5:"john1";s:2:"id";s:1:"1";s:8:"classify";s:1:"1";}'),
('e33949cca7b97e1dd7052283483ba361', '::1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_8_5) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/30.0.1599.101 Safari/537.36', 1383035662, 'a:5:{s:9:"user_data";s:0:"";s:8:"is_login";b:1;s:8:"nickname";s:5:"admin";s:2:"id";s:1:"2";s:8:"classify";s:1:"0";}');

-- --------------------------------------------------------

--
-- Table structure for table `tag_essay`
--

CREATE TABLE `tag_essay` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `essay_id` int(11) NOT NULL,
  `prompt` text COLLATE utf8_unicode_ci NOT NULL,
  `txt` text COLLATE utf8_unicode_ci NOT NULL,
  `draft` tinyint(4) NOT NULL,
  `submit` tinyint(4) NOT NULL,
  `sub_date` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=4 ;

--
-- Dumping data for table `tag_essay`
--

INSERT INTO `tag_essay` (`id`, `essay_id`, `prompt`, `txt`, `draft`, `submit`, `sub_date`) VALUES
(1, 1, 'Some people say that physical exercise should be a required part of every school day. Other people believe that students should spend the whole school day on academic studies. Which opinion do you agree with? Use specific reasons and details to support your answer.', '<IN>Many school students must study their text books and tests during the class time. When I was a school student in my country, many students were studying a lot. Some friends were playing their exercise for much great health if they had a time. They were always healthy body, and they never had any disease because of their good health. <TS>I think that physical exercise should be a required part of every school day for many students’ health.</TS></IN>\n \n<BO1><MI1>First reason is that physical exercise can help to make a good studying for many students.</MI1> <SI1>Like my friends’ great health, the good health can make good body. <TR>Also</TR>, it can make a good study for many students, because good health body can help to concentrate studying when students try to study themselves. If they don’t have a good health, the terrible disease will bother you. <TR>So</TR>, if school have a physical exercise everyday, it will be better.</SI1></BO1> \n\n<BO2><MI2>Another reason is that physical exercise can help to make a successful life for many students.</MI2> <SI2><EX><TR>For example</TR>, many students, who have the best grade in the world with a good health, have a good chance to make their dreams. Their ability can join with a great job like a U.S air force, NASA member, or pilot.</EX> These jobs are very popular for many people. If I have the best grade and great health, these job isn’t difficult for me.</SI2></BO2>\n\n<CO><TR>In conclusion</TR>, requiring physical exercise can make many students’ studying and successful life. Without this in school, maybe some students will have a trouble with disease and weak body which are reducing concentration and hard studying for them. <TR>So</TR>, If school admit this rule, many student will have a great job for their dream.</CO>', 1, 1, '0000-00-00 00:00:00'),
(2, 2, 'Do you agree or disagree with the following statement? Reading fiction (such as novels and short stories) is more enjoyable than watching movies. Use specific reasons and examples to explain your position.', '<IN><TR>Long time ago</TR>, many people thought reading books like short stories or novels are enjoyable things. <TR>But today</TR>, this sentence is just old saying, because many people think that the developing of television and screen are more enjoyable than books. <TS><TR>So</TR>, I think I disagree with this following statement.</TS> I have some reasons to explain about my choice which are the screen action and easy to understand story.</IN>\n \n<BO1><MI1>The first reason is watching movie is more enjoyable than reading books because of screen action of the movie.</MI1> <SI1>If many people watch the movie in theater, they can memorize about all of story. Watching screen is very easier to memorize story than reading books for people. Even though reading book is very good for many people to making imagination or creation, it doesn’t help to memorize the whole story of book correctly.</SI1><BO1>\n\n<BO2><MI2><TR>Furthermore</TR>, watching movie is making fun to many people who are watching.</MI2> <SI2>Reading books also make fun to readers, but this is not easy because for finding funny part, many readers must imagine the story from their mind. Imaging the story needs more time to understand about funny parts. So I think watching movie is very convenient for many people because movie can make fun very easily by its screen.</SI2></BO2>\n\n<CO><TR>In conclusion</TR>, many people said that watching movie is more enjoyable than reading books because of its understanding story very easily and correctly. <TR>So</TR>, many people want to watch the movie instead of reading books today. After 100 years later, the book will be useless for many people I think because of useful and fun watching movie.</CO>', 1, 1, '0000-00-00 00:00:00'),
(3, 8, 'People attend college or university for many different reasons (for example, new experiences, career preparation, increased knowledge). Why do you think people attend college or university? Use specific reasons and examples to support your answer.', '<IN><TR>In these days</TR>, people attend college or university for a lot of reasons. those depend on their indivisual view. <TS><TR>But</TR>, <TR>In my case</TR>, I think that people want to attend college to learn something what they do easily or systemically, to get oppotunity for their future job,and to get to meet peer group who is student with same major.</TS></IN>\n\n<BO1><MI1><TR>First</TR>, many people attend college to learn something easily.</MI1> <SI1>If you study somthing what you intersted by youself, you may do very hardly. But professors who is specalist give you systemic information. If you have some question, you may ask them and you may use library in university to find answer.</SI1></BO1> \n\n<BO2><MI2><TR>Second</TR>, the career what graduated college can give more chance when you get a job.</MI2> <SI2>If you get the diploma fo high school, you can''t find the job what you desired. <EX><TR>For example</TR>, if I want to get registerd nursing job, I can''t submit my resume to hospital where I want to work. Because they want registered nurse who graduated university as employee.</EX></SI2></BO2>\n\n<BO3><MI3><TR>Third</TR>, When you attend college, you may chance to meet peer group who have same major or interesting something.</MI3> <SI3>Because there are a lot of clubs in the college. You can attend anywhere you intrested easily.</SI3></BO3>\n\n<CO><TR>In conclusion</TR>, I think people attend college to learn knowledge, career preparation for future job and to meet their peer group with same major.</CO>', 1, 1, '2013-10-24 15:19:35');

-- --------------------------------------------------------

--
-- Table structure for table `usr`
--

CREATE TABLE `usr` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(128) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(125) COLLATE utf8_unicode_ci NOT NULL,
  `pass` varchar(25) COLLATE utf8_unicode_ci NOT NULL,
  `classify` tinyint(4) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=6 ;

--
-- Dumping data for table `usr`
--

INSERT INTO `usr` (`id`, `name`, `email`, `pass`, `classify`) VALUES
(0, '', '', '', 0),
(1, 'john1', 'aka1@akaon.com', '1111', 1),
(2, 'admin', 'admin@akaon.com', '1111', 0),
(3, 'john2', 'aka2@akaon.com', '1111', 1),
(4, 'john3', 'aka3@akaon.com', '1111', 1),
(5, 'john4', 'aka4@akaon.com', '1111', 1);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

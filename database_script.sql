CREATE DATABASE `school`;




CREATE TABLE `administrators` (
 `id` int(11) NOT NULL AUTO_INCREMENT,
 `name` varchar(100) NOT NULL,
 `role` varchar(100) NOT NULL,
 `phone` varchar(100) NOT NULL,
 `email` varchar(100) NOT NULL,
 `password` varchar(100) NOT NULL,
 `image` varchar(100) NOT NULL,
 PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;



CREATE TABLE `courses` (
 `id` int(11) NOT NULL AUTO_INCREMENT,
 `name` varchar(100) NOT NULL,
 `description` varchar(1000) NOT NULL,
 `image` varchar(100) NOT NULL,
 PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=latin1;





CREATE TABLE `students` (
 `id` int(11) NOT NULL AUTO_INCREMENT,
 `name` varchar(100) NOT NULL,
 `phone` varchar(100) NOT NULL,
 `email` varchar(100) NOT NULL,
 `image` varchar(100) NOT NULL,
 PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=latin1;


CREATE TABLE `course_student` (
 `id` int(11) NOT NULL AUTO_INCREMENT,
 `id_course` int(11) NOT NULL,
 `id_student` int(11) NOT NULL,
 PRIMARY KEY (`id`),
 KEY `id_course` (`id_course`,`id_student`),
 KEY `id_student` (`id_student`),
 CONSTRAINT `course_student_ibfk_1` FOREIGN KEY (`id_student`) REFERENCES `students` (`id`),
 CONSTRAINT `course_student_ibfk_2` FOREIGN KEY (`id_course`) REFERENCES `courses` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=122 DEFAULT CHARSET=latin1;



INSERT INTO `administrators` (`id`, `name`, `role`, `phone`, `email`, `password`, `image`) VALUES
(1, 'Yael', 'owner', '0546720073', 'yaelksam@gmail.com', 'Aa123123', 'default.png');
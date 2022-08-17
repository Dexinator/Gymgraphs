CREATE TABLE `Records_Agosto` (
 `miembro_id` int(11) NOT NULL,
 `miembro_name` text NOT NULL,  
 `bench_press` double,
 `back_squat` double,
  `res_endTime` double
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

ALTER TABLE `Records_Agosto`
ADD PRIMARY KEY (`miembro_id`);

ALTER TABLE `Records_Agosto`
MODIFY `miembro_id` int(11) NOT NULL AUTO_INCREMENT;
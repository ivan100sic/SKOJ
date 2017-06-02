select id, name, statement, author, created_on, status,
 s, ac, tcc from tasks t9 inner join (
 select t3.task_id, s, ac from (
  select task_id, count(user_id) ac from (
   (select * from solved) union (select * from attempted)
  ) t1 group by task_id
 ) t3
 inner join
 (
  select task_id, count(user_id) s from solved group by task_id
 ) t4
 on t3.task_id = t4.task_id
) t7 on t9.id = t7.task_id
 inner join (
  select task_id, count(id) tcc from testcases
  group by task_id
 ) t10 on t10.task_id = t7.task_id
order by t7.task_id;


select id, name,
 s, ac, tcc from tasks t9 inner join (
 select t3.task_id, s, ac from (
  select task_id, count(user_id) ac from (
   (select * from solved) union (select * from attempted)
  ) t1 group by task_id
 ) t3
 inner join
 (
  select task_id, count(user_id) s from solved group by task_id
 ) t4
 on t3.task_id = t4.task_id
) t7 on t9.id = t7.task_id
 inner join (
  select task_id, count(id) tcc from testcases
  group by task_id
 ) t10 on t10.task_id = t7.task_id
order by t7.task_id;
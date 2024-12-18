SELECT projects.name FROM `projects`
JOIN users ON projects.author_id = users.id
WHERE users.email = 'platon429@mail.com';

SELECT tasks.*, projects.name as project_name FROM `tasks` 
JOIN projects ON tasks.project_id = projects.id
WHERE tasks.author_id=2;

SELECT COUNT(id) tasks_count FROM tasks WHERE tasks.author_id = 2 AND tasks.is_complete = 0;

SELECT COUNT(tasks.id) task_count, projects.name FROM tasks
JOIN projects ON tasks.project_id = projects.id
WHERE tasks.author_id = 2
GROUP BY tasks.project_id;

SELECT name FROM `projects` WHERE author_id=2 GROUP BY name;

SELECT projects.name, tasks.name FROM projects
LEFT JOIN tasks ON projects.id = tasks.project_id;

SELECT projects.name, tasks.name FROM projects
LEFT JOIN tasks ON projects.id = tasks.project_id
WHERE projects.author_id = 2;

SELECT projects.name FROM projects
LEFT JOIN tasks ON projects.id = tasks.project_id
WHERE projects.author_id = 2
GROUP BY projects.name;

SELECT COUNT(projects.id) project_count, projects.name, projects.id FROM projects
LEFT JOIN tasks ON projects.id = tasks.project_id
WHERE projects.author_id = 2
GROUP BY projects.id;

SELECT COUNT(tasks.project_id) project_count, projects.name, projects.id FROM projects
LEFT JOIN tasks ON projects.id = tasks.project_id
WHERE projects.author_id = 2
GROUP BY projects.id;

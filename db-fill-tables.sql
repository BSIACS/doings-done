INSERT INTO users
SET email='john-doe@mail.com', name='John', password='12345';

INSERT INTO users
SET email='platon429@mail.com', name='Platon', password='12345';


INSERT INTO projects
SET name='Входящие', author_id='1';

INSERT INTO projects (name, author_id)
VALUES ('Учеба', 1), ('Работа', 1), ('Домашние дела', 1), ('Авто', 1);

INSERT INTO projects (name, author_id)
VALUES ('Автомобиль', 2), ('Животные', 2), ('Обучение', 2), ('Научная деятельность', 2), ('Досуг', 2);

INSERT INTO tasks (is_complete, name, date_expiration, author_id, project_id)
VALUES
  (true, 'Помыть машину', DATE_SUB(NOW(), INTERVAL 3 DAY), 2, 6),
  (false, 'Покормить и погладить кота', DATE_ADD(NOW(), INTERVAL 4 DAY), 2, 7),
  (false, 'Заменить масло. Заменить маслянный фильтр', NOW(), 2, 6),
  (false, 'Отогнать на диагностику кондиционера', DATE_ADD(NOW(), INTERVAL 1 DAY), 2, 6),
  (false, 'Купить клетку для попугая', DATE_ADD(NOW(), INTERVAL 2 DAY), 2, 7),
  (false, 'Отвезти кота ветеринару', DATE_ADD(NOW(), INTERVAL 3 DAY), 2, 7);

INSERT INTO tasks (is_complete, name, date_expiration, author_id, project_id)
VALUES
  (false, 'Выгулять собаку', DATE_ADD(NOW(), INTERVAL 1 DAY), 1, 4);
  
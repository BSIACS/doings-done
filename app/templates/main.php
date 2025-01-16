<section class="content__side">
  <h2 class="content__side-heading">Проекты</h2>
  <nav class="main-navigation">
    <ul class="main-navigation__list">
      <li class="main-navigation__list-item<?=
                                            $filterByProjectId == null ? " main-navigation__list-item--active" : '';
                                            ?>">
        <a class="main-navigation__list-item-link"
          href="#">
          Все
        </a>
        <span class="main-navigation__list-item-count">
          <?php
          foreach ($projects as $key => $value):
            $projectCount += $value['project_count'];
          endforeach;
          ?>
          <?= $projectCount ?>
        </span>
      </li>
      <?php foreach ($projects as $key => $value): ?>
        <li class="main-navigation__list-item<?=
                                              $filterByProjectId == $value['id'] ? ' main-navigation__list-item--active' : '';
                                              ?>">
          <a class="main-navigation__list-item-link"
            href="#"
            data-project_id="<?= $value['id'] ?>">
            <?= $value['name']; ?>
          </a>
          <span class="main-navigation__list-item-count"> <?= $value['project_count']; ?> </span>
        </li>
      <?php endforeach; ?>
    </ul>
  </nav>

  <a class="button button--transparent button--plus content__side-button"
    href="../add-project.php">Добавить проект
  </a>
</section>

<main class="content__main">
  <h2 class="content__main-heading">Список задач</h2>
  <form class="search-form" action="index.php" method="post" autocomplete="off">
    <input class="search-form__input" type="text" name="" value="" placeholder="Поиск по задачам">

    <input class="search-form__submit" type="submit" name="" value="Искать">
  </form>

  <div class="tasks-controls">
    <nav class="tasks-switch">
      <a href="#" class="tasks-switch__item<?= $filterByTaskGroup == TASK_FILTER_VALUES['all'] ? ' tasks-switch__item--active' : '' ?>" data-filter_by_task_group=<?= TASK_FILTER_VALUES['all'] ?>>Все задачи</a>
      <a href="#" class="tasks-switch__item<?= $filterByTaskGroup == TASK_FILTER_VALUES['today'] ? ' tasks-switch__item--active' : '' ?>" data-filter_by_task_group=<?= TASK_FILTER_VALUES['today'] ?>>Повестка дня</a>
      <a href="#" class="tasks-switch__item<?= $filterByTaskGroup == TASK_FILTER_VALUES['tomorrow'] ? ' tasks-switch__item--active' : '' ?>" data-filter_by_task_group=<?= TASK_FILTER_VALUES['tomorrow'] ?>>Завтра</a>
      <a href="#" class="tasks-switch__item<?= $filterByTaskGroup == TASK_FILTER_VALUES['overdue'] ? ' tasks-switch__item--active' : '' ?>" data-filter_by_task_group=<?= TASK_FILTER_VALUES['overdue'] ?>>Просроченные</a>
    </nav>

    <label class="checkbox">
      <input class="checkbox__input visually-hidden show_completed" type="checkbox" <?= $showCompleteTasks ? 'checked' : '' ?>>
      <span class="checkbox__text">Показывать выполненные</span>
    </label>
  </div>

  <table class="tasks">
    <?php foreach ($tasks as $key => $value): ?>
      <tr class="tasks__item task<?=
                                  ($value['date_expiration'] != null && getTimeDiffInSec($value['date_expiration']) < $SECONDS_PER_DAY
                                    && getTimeDiffInSec($value['date_expiration']) >= -$SECONDS_PER_DAY) ? ' task--important' : null
                                  ?><?=
                                    $value["is_complete"] == true ? ' task--completed' : null
                                    ?>">
        <td class="task__select">
          <label class="checkbox task__checkbox">
            <input class="checkbox__input visually-hidden set-completed" type="checkbox" <?= $value["is_complete"] == true ? 'checked' : '' ?> data-task_id=<?= $value["id"] ?>>
            <span class="checkbox__text"><?= $value["name"] ?></span>
          </label>
        </td>
        <td class="task__file">
          <?php if ($value["file_path"]): ?>
            <a class="download-link" href="<?= $value["file_path"] ?>"><?= pathToFileName($value["file_path"]) ?></a>
          <?php endif ?>
        </td>
        <td class="task__date"><?= $value["date_expiration"] ?></td>
        <!-- <td class="task__controls">
        </td> -->
      </tr>
    <?php endforeach ?>
  </table>
</main>
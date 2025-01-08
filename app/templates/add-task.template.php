<section class="content__side">
  <h2 class="content__side-heading">Проекты</h2>
  <nav class="main-navigation">
    <ul class="main-navigation__list">
      <li class="main-navigation__list-item">
        <div class="main-navigation__list-item-link" disabled>
          Все
        </div>
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
        <li class="main-navigation__list-item">
          <div class="main-navigation__list-item-link" disabled
            data-project_id="<?= $value['id'] ?>">
            <?= $value['name']; ?>
          </div>
          <span class="main-navigation__list-item-count"> <?= $value['project_count']; ?> </span>
        </li>
      <?php endforeach; ?>
    </ul>
  </nav>

  <a class="button button--transparent button--plus content__side-button" href="../add-project.php">Добавить проект</a>
</section>

<main class="content__main">
  <h2 class="content__main-heading">Добавление задачи</h2>

  <form class="form" action="" method="post" autocomplete="off">
    <div class="form__row">
      <label class="form__label" for="name">Название <sup>*</sup></label>

      <input class="form__input <?=
                                isset($validationErrors['name']) ? 'form__input--error' : null
                                ?>" type="text" name="name" id="name" value="" placeholder="Введите название">
      <?= isset($validationErrors['name']) ? "<p class='form__message'>" . $validationErrors["name"][0] . "</p>" : null ?>
    </div>

    <div class="form__row">
      <label class="form__label" for="project">Проект <sup>*</sup></label>

      <select class="form__input form__input--select <?=
                                                  isset($validationErrors['project']) ? 'form__input--error' : null
                                                  ?>" name="project" id="project">
        <?php foreach ($projects as $key => $value): ?>
          <option value="<?= $value['id'] ?>"><?= $value['name'] ?></option>
        <?php endforeach ?>
        <option value="555">Входящие</option>
      </select>
      <?= isset($validationErrors['project']) ? "<p class='form__message'>" . $validationErrors["project"][0] . "</p>" : null ?>
    </div>

    <div class="form__row">
      <label class="form__label" for="date">Дата выполнения</label>

      <input class="form__input form__input--date <?=
                                                  isset($validationErrors['date']) ? 'form__input--error' : null
                                                  ?>" type="text" name="date" id="date" value="" placeholder="Введите дату в формате ГГГГ-ММ-ДД">
      <?= isset($validationErrors['date']) ? "<p class='form__message'>" . $validationErrors["date"][0] . "</p>" : null ?>

    </div>

    <div class="form__row">
      <label class="form__label" for="file">Файл</label>

      <div class="form__input-file">
        <input class="visually-hidden" type="file" name="file" id="file" value="">

        <label class="button button--transparent" for="file">
          <span>Выберите файл</span>
        </label>
      </div>
    </div>

    <div class="form__row form__row--controls">
      <input class="button" type="submit" name="" value="Добавить">
    </div>
  </form>
</main>
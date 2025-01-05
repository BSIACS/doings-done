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
</section>

<main class="content__main">
  <h2 class="content__main-heading">Добавление проекта</h2>

  <form class="form" action="" method="post" autocomplete="off">
    <div class="form__row">
      <label class="form__label" for="project_name">Название <sup>*</sup></label>

      <input class="form__input <?=
                                isset($validationErrors['name']) ? 'form__input--error' : null
                                ?>" type="text" name="name" id="project_name" value="" placeholder="Введите название проекта">
      <?= isset($validationErrors['name']) ? "<p class='form__message'>" . $validationErrors["name"][0] . "</p>" : null ?>
    </div>

    <div class="form__row form__row--controls">
      <input class="button" type="submit" name="" value="Добавить">
    </div>
  </form>
</main>
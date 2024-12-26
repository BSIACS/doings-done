'use strict';

var $checkbox = document.getElementsByClassName('show_completed');

if ($checkbox.length) {
  $checkbox[0].addEventListener('change', function (event) {
    var is_checked = +event.target.checked;

    var searchParams = new URLSearchParams(window.location.search);
    searchParams.set('show_completed', is_checked);

    window.location = '/index.php?' + searchParams.toString();
  });
}

var $taskCheckboxes = document.getElementsByClassName('set-completed');

if ($taskCheckboxes.length) {
  for (let i = 0; i < $taskCheckboxes.length; ++i) {
    $taskCheckboxes[i].addEventListener('change', function (event) {
      event.preventDefault();
      if (event.target.classList.contains('checkbox__input')) {
        var el = event.target;
        var isChecked = el.checked;
        var taskId = el.dataset.task_id;

        var searchParams = new URLSearchParams(window.location.search);
        searchParams.set('task_id', taskId);
        searchParams.set('checked', isChecked);
        window.location = '/index.php?' + searchParams.toString();
      }
    });
  }
}

flatpickr('#date', {
  enableTime: false,
  dateFormat: "Y-m-d",
  locale: "ru"
});

var project_links = document.getElementsByClassName('main-navigation__list-item-link');

if (project_links.length > 0) {
  for (let i = 0; i < project_links.length; ++i) {
    project_links[i].addEventListener('click', (evt) => {
      evt.preventDefault();
      var queryParams = new URLSearchParams(window.location.search);
      if (project_links[i].dataset.project_id) {
        queryParams.set('filter_by_project_id', project_links[i].dataset?.project_id);
        window.location = '/index.php?' + queryParams.toString();
      }
      else {
        queryParams.delete('filter_by_project_id');
        window.location = '/index.php?' + queryParams.toString();
      }

    });
  }
};

var taskSwitchLinks = document.getElementsByClassName('tasks-switch__item');

if (taskSwitchLinks.length > 0) {
  for (let i = 0; i < taskSwitchLinks.length; ++i) {
    taskSwitchLinks[i].addEventListener('click', (evt) => {
      evt.preventDefault();
      var queryParams = new URLSearchParams(window.location.search);
      queryParams.set('filter_by_task_group', taskSwitchLinks[i].dataset?.filter_by_task_group);
      window.location = '/index.php?' + queryParams.toString();
    });
  }
};



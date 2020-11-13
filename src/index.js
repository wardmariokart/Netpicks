require('./style.css');
{
  const init = () => {
    const $todosList = document.querySelector(`#todosList`);
    if ($todosList) {
      loadTodos();
    }

    const $insertTodoForm = document.querySelector(`#insertTodoForm`);
    if ($insertTodoForm) {
      $insertTodoForm.addEventListener(`submit`, handleSubmitInsertTodoForm);
    }
  };

  const loadTodos = async () => {
    const response = await fetch("index.php", {// OF: url = window.location.href.split(`?`)[0];
      headers: new Headers({
        Accept: 'application/json'
      })
    });
    const todos = await response.json();
    handleLoadTodos(todos);
  };

  const handleLoadTodos = data => {
    const $todosList = document.querySelector(`#todosList`);
    $todosList.innerHTML = data.map(todo => createTodoListItem(todo)).join(``);
  };

  const createTodoListItem = todo => {
    return `<li>${todo.text}</li>`;
  };

  // deze functie zal het formulier afhandelen: merk op dat dit een async functie is
  const handleSubmitInsertTodoForm = e => {
    const $form = e.currentTarget;
    e.preventDefault();
    postTodo($form);
  };

  const postTodo = async ($form) => {
    // versturen naar de juiste route op de server en aangeven dat we een JSON response verwachten
    // de parameter body bevat de data (de todo text)
    const response = await fetch($form.getAttribute('action'), {
      method: "POST",
      headers: new Headers({
        Accept: 'application/json'
      }),
      body: new FormData($form)
    });
    // antwoord van PHP. Kan een error bevatten of een lijst van todos
    const returned = await response.json();
    handleLoadSubmit(returned)
  }

  const handleLoadSubmit = data => {
    const $errorText = document.querySelector(`.error--text`);
    $errorText.textContent = '';
    if (data.result === 'ok') {
      const $inputText = document.querySelector(`#inputText`);
      $inputText.value = '';
      // Todos opnieuw fetchen zodat de nieuwste ook getoond wordt
      loadTodos();
    } else {
      if (data.errors.text) {
        $errorText.textContent = data.errors.text;
      }
    }
  };

  init();
}

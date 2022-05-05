<?php

header('Content-Type: text/html; charset=UTF-8');

if ($_SERVER['REQUEST_METHOD'] == 'GET') {

    if(!empty($_GET['goback'])) {
        setcookie('name_value', '', 100000);
        setcookie('email_value', '', 100000);
        setcookie('birth_value', '', 100000);
        setcookie('gender_value', '', 100000);
        setcookie('limbs_value', '', 100000);
        setcookie('select_value', '', 100000);
        setcookie('bio_value', '', 100000);
        setcookie('policy_value', '', 100000);
        setcookie('login_value', '', 100000);
        header('Location: admin.php');
    }

    $messages = array();

    if (!empty($_COOKIE['update'])) {
        setcookie('update', 100000);

        $messages[] = 'Данные изменены';
    }
    $errors = array();
    $errors['name'] = !empty($_COOKIE['name_error']);
    $errors['email'] = !empty($_COOKIE['email_error']);
    $errors['birth'] = !empty($_COOKIE['birth_error']);
    $errors['gender'] = !empty($_COOKIE['gender_error']);
    $errors['limbs'] = !empty($_COOKIE['limbs_error']);
    $errors['select'] = !empty($_COOKIE['select_error']);
    $errors['bio'] = !empty($_COOKIE['bio_error']);
    $errors['policy'] = !empty($_COOKIE['policy_error']);

    if ($errors['name']) {
        setcookie('name_error', '', 100000);
        $messages[] = '<div class="error">Введите имя.</div>';
    }
    if ($errors['email']) {
        setcookie('email_error', '', 100000);
        $messages[] = '<div class="error">Введите верный email.</div>';
    }
    if ($errors['birth']) {
        setcookie('birth_error', '', 100000);
        $messages[] = '<div class="error">Введите корректную дату рождения.</div>';
    }
    if ($errors['gender']) {
        setcookie('gender_error', '', 100000);
        $messages[] = '<div class="error">Выберите пол.</div>';
    }
    if ($errors['limbs']) {
        setcookie('limbs_error', '', 100000);
        $messages[] = '<div class="error">Выберите количество конечностей.</div>';
    }
    if ($errors['select']) {
        setcookie('select_error', '', 100000);
        $messages[] = '<div class="error">Выберите суперспособнос(ть/ти).</div>';
    }
    if ($errors['bio']) {
        setcookie('bio_error', '', 100000);
        $messages[] = '<div class="error">Расскажите о себе.</div>';
    }
    if ($errors['policy']) {
        setcookie('policy_error', '', 100000);
        $messages[] = '<div class="error">Ознакомтесь с политикой обработки данных.</div>';
    }

    $values = array();
    
    $values['name'] = empty($_COOKIE['name_value']) ? '' : $_COOKIE['name_value'];
    $values['email'] = empty($_COOKIE['email_value']) ? '' : $_COOKIE['email_value'];
    $values['birth'] = empty($_COOKIE['birth_value']) ? '' : $_COOKIE['birth_value'];
    $values['gender'] = empty($_COOKIE['gender_value']) ? '' : $_COOKIE['gender_value'];
    $values['limbs'] = empty($_COOKIE['limbs_value']) ? '' : $_COOKIE['limbs_value'];
    $values['select'] = empty($_COOKIE['select_value']) ? '' : $_COOKIE['select_value'];
    $values['bio'] = empty($_COOKIE['bio_value']) ? '' : $_COOKIE['bio_value'];
    $values['policy'] = empty($_COOKIE['policy_value']) ? '' : $_COOKIE['policy_value'];

} else {
    if(!empty($_POST['edit'])) {
        setcookie('login_value', $_POST['edit'], time() + 12 * 30 * 24 * 60 * 60);
    }
    $errors = FALSE;
    if(!empty($_COOKIE['login_value'])) {
            // проверка поля имени
        if (!preg_match('/^[a-z0-9_\s]+$/i', $_POST['name'])) {
            setcookie('name_error', '1', time() + 24 * 60 * 60);
            $errors = TRUE;
        } else {
            setcookie('name_value', $_POST['name'], time() + 12 * 30 * 24 * 60 * 60);
        }

        // проверка поля email
        if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
            setcookie('email_error', '1', time() + 24 * 60 * 60);
            $errors = TRUE;
        } else {
            setcookie('email_value', $_POST['email'], time() + 12 * 30 * 24 * 60 * 60);
        }

        // проверка поля даты рождения
        $birth = explode('-', $_POST['birth']);
        $age = (int)date('Y') - (int)$birth[0];
        if ($age > 100 || $age < 0) {
            setcookie('birth_error', '1', time() + 24 * 60 * 60);
            $errors = TRUE;
        } else {
            setcookie('birth_value', $_POST['birth'], time() + 12 * 30 * 24 * 60 * 60);
        }

        // проверка поля пола
        if (empty($_POST['gender'])) {
            setcookie('gender_error', '1', time() + 24 * 60 * 60);
            $errors = TRUE;
        } else {
            setcookie('gender_value', $_POST['gender'], time() + 12 * 30 * 24 * 60 * 60);
        }

        // проверка поля количества конечностей
        if (empty($_POST['limbs'])) {
            setcookie('limbs_error', '1', time() + 24 * 60 * 60);
            $errors = TRUE;
        } else {
            setcookie('limbs_value', $_POST['limbs'], time() + 12 * 30 * 24 * 60 * 60);
        }

        // проверка поля суперспособностей
        if (empty($_POST['select'])) {
            setcookie('select_error', '1', time() + 24 * 60 * 60);
            $errors = TRUE;
        } else {
            setcookie('select_value', implode(',', $_POST['select']), time() + 12 * 30 * 24 * 60 * 60);
        }

        // проверка поля биографии
        if (empty($_POST['bio'])) {
            setcookie('bio_error', '1', time() + 24 * 60 * 60);
            $errors = TRUE;
        } else {
            setcookie('bio_value', $_POST['bio'], time() + 12 * 30 * 24 * 60 * 60);
        }

        // проверка поля политики обработки данных 
        if (empty($_POST['policy'])) {
            setcookie('policy_error', '1', time() + 24 * 60 * 60);
            $errors = TRUE;
        } else {
            setcookie('policy_value', $_POST['policy'], time() + 12 * 30 * 24 * 60 * 60);
        }
    } else {
        $user = 'u47572';
        $pass = '4532025';
        $db = new PDO('mysql:host=localhost;dbname=u47572', $user, $pass, array(PDO::ATTR_PERSISTENT => true));
        $stmt = $db->prepare("SELECT * FROM members WHERE login = ?");
        $stmt->execute(array($_COOKIE['login_value']));
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        $values['name'] = $result['name'];
        $values['email'] = $result['email'];
        $values['birth'] = $result['date'];
        $values['gender'] = $result['gender'];
        $values['limbs'] = $result['limbs'];
        $values['bio'] = $result['bio'];
        $values['policy'] = $result['policy'];

        $powers = $db->prepare("SELECT * FROM powers2 WHERE user_login = ?");
        $powers->execute(array($_COOKIE['login_value']));
        $result = $powers->fetch(PDO::FETCH_ASSOC);
        $values['select'] = $result['powers'];

    }

    if ($errors) {
        header('Location: edit.php');
        exit();
    } else {
        setcookie('name_error', '', 100000);
        setcookie('email_error', '', 100000);
        setcookie('birth_error', '', 100000);
        setcookie('gender_error', '', 100000);
        setcookie('limbs_error', '', 100000);
        setcookie('select_error', '', 100000);
        setcookie('bio_error', '', 100000);
        setcookie('policy_error', '', 100000);
    }

        $name = $values['name'];
        $email = $values['email'];
        $date = $values['birth'];
        $gender = $values['gender'];
        $limbs = $values['limbs'];
        $bio = $values['bio'];
        $policy = $values['policy'];
        $powers = implode(',', $values['select']);

        $user = 'u47572';
        $pass = '4532025';
        $db = new PDO('mysql:host=localhost;dbname=u47572', $user, $pass, array(PDO::ATTR_PERSISTENT => true));

        try {
            $stmt = $db->prepare("UPDATE members SET name = ?, email = ?, date = ?, gender = ?, limbs = ?, bio = ?, policy = ? WHERE login = ?");
            $stmt->execute(array($name, $email, $date, $gender, $limbs, $bio, $policy, $_COOKIE['login_value']));
    
            $superpowers = $db->prepare("UPDATE powers2 SET powers = ? WHERE user_login = ? ");
            $superpowers->execute(array($powers, $_COOKIE['login_value']));
        } catch (PDOException $e) {
            print('Error : ' . $e->getMessage());
            exit();
        }
        setcookie('update', '1');

    // Делаем перенаправление.
    if(empty($_POST['edit'])) {
        header('Location: edit.php');
    }
}?>

<!DOCTYPE html>
<html lang="">

<head>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta charset="utf-8" />
  <link rel="stylesheet" href="./style.css" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" />
  <title>Update <?php echo $values['name']?> data</title>
  <style>
  </style>
</head>

<body>
  <?php
  if (!empty($messages)) {
    print('<div id="messages">');
    // Выводим все сообщения.
    foreach ($messages as $message) {
      print($message);
    }
    print('</div>');
  }

  // Далее выводим форму отмечая элементы с ошибками классом error
  // и задавая начальные значения элементов ранее сохраненными.
  ?>
  <div class="form-container">
    <div class="form-title">
      Форма изменения данных
      <div>
      <a href="edit.php?goback=1">Вернуться</a>
    </div>
    </div>
    <form method="POST" action="">
      <div class="input-group" <?php if ($errors['name']) {
                                  print 'class="error"';
                                } ?>>
        <span class="input-group-text" id="basic-addon1">Имя</span>
        <input type="text" class="form-control" name="name" aria-describedby="basic-addon1" placeholder="Тарас" value="<?php print $values['name']; ?>" />
      </div>
      <div class="input-group" <?php if ($errors['email']) {
                                  print 'class="error"';
                                } ?>>
        <span class="input-group-text" id="basic-addon2">Email</span>
        <input type="text" class="form-control" name="email" aria-describedby="basic-addon2" placeholder="example@mail.ru" value="<?php print $values['email']; ?>" />
      </div>
      <div class="input-group <?php if ($errors['birth']) {
                                print 'class="error"';
                              } ?>">
        <span class="input-group-text" id="basic-addon3">Дата рождения</span>
        <input type="date" class="form-control" aria-describedby="basic-addon3" placeholder="example@mail.ru" name="birth" value="<?php print $values['birth']; ?>" />
      </div>
      <div class="form-check" id="gender-block" <?php if ($errors['gender']) {
                                                  print 'class="error"';
                                                } ?>>
        <span class="input-group-text">Пол</span>
        <div class="genders">
          <div class="male-radio">
            <input class="form-check-input" type="radio" name="gender" value="m" <?php if ($values['gender'] == 'm') {
                                                                                    print('checked');
                                                                                  }; ?> />
            <label class="form-check-label" for="male">Мужской</label>
          </div>
          <div class="female-radio">
            <input class="form-check-input" type="radio" name="gender" value="f" <?php if ($values['gender'] == 'f') {
                                                                                    print('checked');
                                                                                  }; ?> />
            <label class="form-check-label" for="female">Женский</label>
          </div>
        </div>
      </div>
      <div class="form-check" id="limbs-block">
        <span class="input-group-text block-title">Кол-во конечностей</span>
        <div class="limbs" <?php if ($errors['limbs']) {
                              print 'class="error"';
                            } ?>>
          <div class="limbs-radio">
            <input class="form-check-input" type="radio" name="limbs" value="1" <?php if ($values['limbs'] == '1') {
                                                                                  print('checked');
                                                                                }; ?> />
            <label class="form-check-label" for="male">1</label>
          </div>
          <div class="limbs-radio">
            <input class="form-check-input" type="radio" name="limbs" value="2" <?php if ($values['limbs'] == '2') {
                                                                                  print('checked');
                                                                                }; ?> />
            <label class="form-check-label" for="female">2</label>
          </div>
          <div class="limbs-radio">
            <input class="form-check-input" type="radio" name="limbs" value="3" <?php if ($values['limbs'] == '3') {
                                                                                  print('checked');
                                                                                }; ?> />
            <label class="form-check-label" for="female">3</label>
          </div>
          <div class="limbs-radio">
            <input class="form-check-input" type="radio" name="limbs" value="4" <?php if ($values['limbs'] == '4') {
                                                                                  print('checked');
                                                                                }; ?> />
            <label class="form-check-label" for="female">4</label>
          </div>
        </div>
      </div>
      <select class="form-select form-select-lg mb-2" name="select[]" multiple <?php if ($errors['select']) {
                                                                                  print 'class="error"';
                                                                                } ?>>
        <option value="inf" <?php foreach (explode(',', $values['select']) as $value) {
                              if ($value == "inf") print('selected');
                            } ?>>Бессмертие</option>

        <option value="through" <?php foreach (explode(',', $values['select']) as $value) {
                                  if ($value == "through") print('selected');
                                } ?>>Прохождение сквозь стены</option>

        <option value="levitation" <?php foreach (explode(',', $values['select']) as $value) {
                                      if ($value == "levitation") print('selected');
                                    } ?>>Левитация</option>

      </select>
      <div class="input-group">
        <textarea class="form-control" placeholder="Расскажите о себе..." name="bio" <?php if ($errors['bio']) {
                                                                                        print 'class="error"';
                                                                                      } ?>><?php print $values['bio']; ?></textarea>
      </div>
      <div class="form-check">
        <input class="form-check-input" type="checkbox" value="y" id="policy" name="policy" <?php if ($errors['policy']) {
                                                                                              print 'class="error"';
                                                                                            } ?> <?php if ($values['policy'] == 'y') {
                                                                                                    print('checked');
                                                                                                  }; ?> />
        <label class="form-check-label" for="policy">Согласен с <a href="./task3.html">политикой обработки данных*</a>.</label>
      </div>
      <button class="btn btn-primary" type="submit" id="send-btn">Изменить</button>
    </form>
  </div>
</body>

</html>

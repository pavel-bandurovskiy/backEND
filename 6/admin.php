<?php

$user = 'u47572';
$pass = '4532025';
$db = new PDO('mysql:host=localhost;dbname=u47572', $user, $pass, array(PDO::ATTR_PERSISTENT => true));

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (!empty($_POST['delete'])) {
        $stmt = $db->prepare("SELECT * FROM members WHERE login = ?");
        $stmt->execute(array($_POST['delete']));
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        if (empty($result)) {
            print('<p>Ошибка при удалении данных</p>');
        } else {
            $stmt = $db->prepare("DELETE FROM members WHERE login = ?");
            $stmt->execute(array($_POST['delete']));

            $powers = $db->prepare("DELETE FROM powers2 where user_login = ?");
            $powers->execute(array($_POST['delete']));
            header('Location: ?delete_error=0');
        }
    } else {
        $errors = FALSE;

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
        $select = implode(',', $values['select']);
        $user = 'u47572';
        $pass = '4532025';
        $db = new PDO('mysql:host=localhost;dbname=u47572', $user, $pass, array(PDO::ATTR_PERSISTENT => true));

        $member_id = $_COOKIE['user_id'];

        try {
            $stmt = $db->prepare("SELECT login FROM members WHERE id = ?");
            $stmt->execute(array($member_id));
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            $stmt = $db->prepare("UPDATE members SET name = ?, email = ?, date = ?, gender = ?, limbs = ?, bio = ?, policy = ? WHERE login = ?");
            $stmt->execute(array($name, $email, $date, $gender, $limbs, $bio, $policy, $result['login']));

            $superpowers = $db->prepare("UPDATE powers2 SET powers = ? WHERE user_login = ? ");
            $superpowers->execute(array($select, $result['login']));
        } catch (PDOException $e) {
            print('Error : ' . $e->getMessage());
            exit();
        }

        // Делаем перенаправление.
        header('Location: ./?upd=1');
    }
} else {
    $messages = array();
    if (!empty($_GET['upd'])) {
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
    if (empty($_GET['id'])) {
        $values['name'] = empty($_COOKIE['name_value']) ? '' : $_COOKIE['name_value'];
        $values['email'] = empty($_COOKIE['email_value']) ? '' : $_COOKIE['email_value'];
        $values['birth'] = empty($_COOKIE['birth_value']) ? '' : $_COOKIE['birth_value'];
        $values['gender'] = empty($_COOKIE['gender_value']) ? '' : $_COOKIE['gender_value'];
        $values['limbs'] = empty($_COOKIE['limbs_value']) ? '' : $_COOKIE['limbs_value'];
        $values['select'] = empty($_COOKIE['select_value']) ? '' : $_COOKIE['select_value'];
        $values['bio'] = empty($_COOKIE['bio_value']) ? '' : $_COOKIE['bio_value'];
        $values['policy'] = empty($_COOKIE['policy_value']) ? '' : $_COOKIE['policy_value'];
    } else {
        $user = 'u47572';
        $pass = '4532025';
        $member_id = $_GET['id'];

        $db = new PDO('mysql:host=localhost;dbname=u47572', $user, $pass, array(PDO::ATTR_PERSISTENT => true));
        $stmt = $db->prepare("SELECT * FROM members WHERE id = ?");
        $stmt->execute(array($member_id));
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        $values['name'] = $result['name'];
        $values['email'] = $result['email'];
        $values['birth'] = $result['date'];
        $values['gender'] = $result['gender'];
        $values['limbs'] = $result['limbs'];
        $values['bio'] = $result['bio'];
        $values['policy'] = $result['policy'];

        setcookie('user_id', $member_id, time() + 12 * 30 * 24 * 60 * 60);

        $powers = $db->prepare("SELECT * FROM powers2 WHERE user_login = ?");
        $powers->execute(array($result['login']));
        $result = $powers->fetch(PDO::FETCH_ASSOC);
        $values['select'] = $result['powers'];
    }
    include('form.php');
}

if (!empty($_SERVER['PHP_AUTH_USER']) && !empty($_SERVER['PHP_AUTH_PW'])) {
    try {
        $stmt = $db->prepare("SELECT * FROM admins WHERE login = ?");
        $stmt->execute(array($_SERVER['PHP_AUTH_USER']));
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        print('Error : ' . $e->getMessage());
        exit();
    }

    if (empty($result['password'])) {
        header('HTTP/1.1 401 Unanthorized');
        header('WWW-Authenticate: Basic realm="My site"');
        print('<h1>401 Неверный логин</h1>');
        exit();
    }

    if ($result['password'] != md5($_SERVER['PHP_AUTH_PW'])) {
        header('HTTP/1.1 401 Unanthorized');
        header('WWW-Authenticate: Basic realm="My site"');
        print('<h1>401 Неверный пароль</h1>');
        exit();
    }

    print('Вы успешно авторизовались и видите защищенные паролем данные.');

    $stmt = $db->prepare("SELECT * FROM members");
    $stmt->execute([]);
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
} else {
    header('HTTP/1.1 401 Unanthorized');
    header('WWW-Authenticate: Basic realm="My site"');
    print('<h1>401 Требуется авторизация</h1>');
    exit();
}
?>
<!DOCTYPE html>
<html lang="">

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta charset="utf-8" />
    <link rel="stylesheet" href="./style.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" />
    <title>Admin</title>
</head>

<body>
    <div class="records-list">
        <table>
            <tr>
                <th>Имя</th>
                <th>Email</th>
                <th>Дата рождения</th>
                <th>Пол</th>
                <th>Конечности</th>
                <th>Суперспособности</th>
                <th>Биография</th>
            </tr>
            <?php
            if (!empty($result)) {
                foreach ($result as $value) {
            ?>
                    <tr>
                        <td><?php echo $value['name'] ?></td>
                        <td><?php echo $value['email'] ?></td>
                        <td><?php echo $value['date'] ?></td>
                        <td><?php echo $value['limbs'] ?></td>
                        <td><?php echo $value['gender'] ?></td>
                        <td>
                            <?php
                            $powers = $db->prepare("SELECT * FROM powers2 where user_login = ?");
                            $powers->execute(array($value['login']));
                            $superpowers = $powers->fetch(PDO::FETCH_ASSOC);
                            echo $superpowers['powers'];
                            ?>
                        </td>
                        <td id="bio">
                            <?php echo $value['bio'] ?>
                        </td>
                        <td class="edit-buttons">
                            <a id="edit" href="admin.php?id=<?php echo $value['id'] ?>">Edit</a>
                        </td>
                        <td class="edit-buttons">
                            <form action="" method="post">
                                <input value="<?php echo $value['login'] ?>" name="delete" type="hidden" />
                                <button id="delete">Delete</button>
                            </form>
                        </td>
                    </tr>
            <?php
                }
            } else {
                echo "Записи не найдены";
            }
            ?>
        </table>
    </div>
</body>

</html>

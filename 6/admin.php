<?php

$user = 'u47572';
$pass = '4532025';
$db = new PDO('mysql:host=localhost;dbname=u47572', $user, $pass, array(PDO::ATTR_PERSISTENT => true));

if (!empty($_SERVER['PHP_AUTH_USER']) && !empty($_SERVER['PHP_AUTH_PW'])) {
  try {
    $stmt = $db->prepare("SELECT * FROM admins WHERE login = ?");
    $stmt->execute(array($_SERVER['PHP_AUTH_USER']));
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
  } catch (PDOException $e) {
    print('Error : ' . $e->getMessage());
    exit();
  }

  if(empty($result['password'])) {
    header('HTTP/1.1 401 Unanthorized');
    header('WWW-Authenticate: Basic realm="My site"');
    print('<h1>401 Неверный логин</h1>');
    exit();
  }
  
  if($result['password'] != md5($_SERVER['PHP_AUTH_PW'])) {
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
                if(!empty($result)) {
                    foreach($result as $value) {
            ?>
            <tr>
                <td><?php echo $value['name']?></td>
                <td><?php echo $value['email']?></td>
                <td><?php echo $value['date']?></td>
                <td><?php echo $value['limbs']?></td>
                <td><?php echo $value['gender']?></td>
                <td>
                    <?php 
                    $powers = $db->prepare("SELECT * FROM powers2 where user_login = ?");
                    $powers->execute(array($result['login']));
                    $superpowers = $powers->fetch(PDO::FETCH_ASSOC);
                    echo $superpowers['powers'];
                    ?>
                    </td>
                <td id="bio">
                    <?php echo $value['bio']?>
                </td>
                <td class="edit-buttons">
                    <form action="" method="post">
                        <input value="<?php echo $value['id']?>" type="hidden"/>
                        <button id="edit">Edit</button>
                    </form>
                </td>
                <td class="edit-buttons">
                    <form action="" method="post">
                        <input value="<?php echo $value['id']?>" type="hidden"/>
                        <button id="delete">Delete</button>
                    </form>
                </td>
            </tr>
            <?php
                    }
                }
                else {
                    echo "No Record Found";
                }
            ?>
        </table>
    </div>
</body>
</html>

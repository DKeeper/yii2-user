<?php

return [

    // models
    'ID' => 'Номер',
    'User ID' => 'Номер пользователя',
    'Create time' => 'Дата создания',
    'Update time' => 'Дата обновления',
    'Full Name' => 'Полное имя',

    'Name' => 'Имя',

    'Status' => 'Статус',
    'Email' => 'E-mail адрес',
    'New email' => 'Новый E-mail адрес',
    'Email confirmation' => 'E-mail подтвержден',
    'Phone' => '№ телефона',
    'New phone' => 'Новый E-mail адрес',
    'Phone confirmation' => 'Телефон подтвержден',
    'Username' => 'Логин пользователя',
    'Password' => 'Пароль',
    'Auth key' => 'Ключ авторизации',
    'Auth key to' => 'Ключ авторизации до',
    'Login IP' => 'IP авторизации',
    'Login time' => 'Дата авторизации',
    'Create IP' => 'IP создания',
    'Ban to' => 'Дата бана',
    'Ban reason' => 'Причина бана',
    'Current password' => 'Текущий пароль',
    'New password' => 'Новый пароль',

    // models/forms
    'Email not found' => 'E-mail не найдено',
    'Email/username not found' => 'E-mail/имя пользователя не найдено',
    'Username not found' => 'Пользователь не найден',
    'User is banned - {banReason}' => 'Пользователя блокировано - {banReason}',
    'Incorrect password' => 'Неверный пароль',
    'Remember Me' => 'Запомнить меня',
    'Email is already active' => 'E-mail уже активен',

    // controllers
    'Successfully registered [ {displayName} ]' => 'Успешно зарегистрирован [ {displayName} ]',
    ' - Please check your email to confirm your account' => ' - Проверьте свою почту, чтобы подтвердить свой ​​аккаунт',
    'Account updated' => 'Дата обновления аккаунта',
    'Profile updated' => 'Дата обновления профиля',
    'User is not active, confirmation email with ney key resent' => 'Пользователь не подтвержден, по электронной почте заново отправлено подтверждение с новым ключем',
    'Email change cancelled' => 'Изменение E-mail отменено',
    'Instructions to reset your password have been sent' => 'Инструкции по изменению вашего пароля, были отправлены в ваш почтовый ящик',

    // mail
    'Please confirm your email address by clicking the link below:' => 'Пожалуйста, подтвердите свой ​​адрес электронной почты, нажав на ссылку ниже:',
    'Please use this link to reset your password:' => 'Пожалуйста, воспользуйтесь этой ссылкой для восстановления пароля:',

    // admin views
    'Users' => 'Пользователи',
    'Banned' => 'Заблокировано',
    'Create' => 'Создано',
    'Update' => 'Обновлено',
    'Delete' => 'Удалено',
    'Search' => 'Поиск',
    'Reset' => 'Сбросить',
    'Create {modelClass}' => 'Создать {modelClass}',
    'Update {modelClass}: ' => 'Изменить {modelClass}: ',
    'Are you sure you want to delete this item?' => 'Вы уверены, что хотите удалить ваш аккаунт?',

    // default views
    'Account' => 'Аккаунт',
    'Pending email confirmation: [ {newEmail} ]' => 'В ожидании подтверждения по электронной почте: [ {newEmail} ]',
    'Cancel' => 'Отменить',
    'Changing your email requires email confirmation' => 'Изменение электронной почты требует подтверждения нового адреса',
    'Confirmed' => 'Подтверждено',
    'Error' => 'Ошибка',
    'Your email [ {email} ] has been confirmed' => 'Ваш адрес электронной почты [ {email} ] был подтвержден',
    'Go to my profile' => 'К моему профилю',
    'Go home' => 'На главную',
    'Log in here' => 'Войти сейчас',
    'Invalid key' => 'Неверный ключ',
    'Forgot password' => 'Забыли пароль',
    'Submit' => 'Отправить',
    'Yii 2 User' => 'Yii 2 пользователь',
    'Login' => 'Войти',
    'Register' => 'Зарегистрироваться',
    'Logout' => 'Выйти',
    'Resend confirmation email' => 'Повторно отправить подтверждение по электронной почте',
    'Profile' => 'Профиль',
    'Please fill out the following fields to login:' => 'Пожалуйста, заполните следующие поля для входа:',
    'Please fill out the following fields to register:' => 'Пожалуйста, заполните следующие поля для регистрации:',
    'Resend' => 'Повторить',
    'Password has been reset' => 'Пароль восстановлено',

    '{attribute} can contain only latin letters, numbers, and "_"' => '{attribute} может содержать только латинские буквы, цифры, и "_"',
    '{attribute} can contain only "+" and numbers' => '{attribute} может содержать только "+" и цифры',
];
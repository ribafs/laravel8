# Desabilitar o link de registro

config/fortify.php

Comentar a linha com registration

    'features' => [
        // Features::registration(),
        Features::resetPasswords(),
        // Features::emailVerification(),
        Features::updateProfileInformation(),
        Features::updatePasswords(),
        Features::twoFactorAuthentication([
            'confirmPassword' => true,
        ]),
    ],


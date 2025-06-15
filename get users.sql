SELECT 
    users.name AS Nama,
    users.username AS Username,
    users.email AS Email,
    '123456789' AS Password,
    GROUP_CONCAT(roles.name SEPARATOR ', ') AS Role
FROM users
LEFT JOIN model_has_roles 
    ON model_has_roles.model_id = users.id AND model_has_roles.model_type = 'App\\Models\\User'
LEFT JOIN roles 
    ON roles.id = model_has_roles.role_id
GROUP BY users.id, users.name, users.username, users.email;

CREATE TABLE usuarios (
    id INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(150) NOT NULL,
    correo VARCHAR(150) NOT NULL UNIQUE,
    PASSWORD VARCHAR(150) NOT NULL
)

-- Password = password1 (todos)
INSERT INTO usuarios (nombre, correo, PASSWORD)
VALUES
    ('Usuario1','usuario1@misitio.cl', '$2y$10$EjRzw5xU7kcUIUnFFrM24u96VbCbX09bvfuF6jdYpk0.v/cH/q.Zi'),
    ('Usuario2','usuario2@misitio.cl', '$2y$10$EjRzw5xU7kcUIUnFFrM24u96VbCbX09bvfuF6jdYpk0.v/cH/q.Zi'),
    ('Usuario3','usuario3@misitio.cl', '$2y$10$EjRzw5xU7kcUIUnFFrM24u96VbCbX09bvfuF6jdYpk0.v/cH/q.Zi'),
    ('Usuario4','usuario4@misitio.cl', '$2y$10$EjRzw5xU7kcUIUnFFrM24u96VbCbX09bvfuF6jdYpk0.v/cH/q.Zi'),
    ('Usuario5','usuario5@misitio.cl', '$2y$10$EjRzw5xU7kcUIUnFFrM24u96VbCbX09bvfuF6jdYpk0.v/cH/q.Zi'),
    ('Usuario6','usuario6@misitio.cl', '$2y$10$EjRzw5xU7kcUIUnFFrM24u96VbCbX09bvfuF6jdYpk0.v/cH/q.Zi'),
    ('Usuario7','usuario7@misitio.cl', '$2y$10$EjRzw5xU7kcUIUnFFrM24u96VbCbX09bvfuF6jdYpk0.v/cH/q.Zi'),
    ('Usuario8','usuario8@misitio.cl', '$2y$10$EjRzw5xU7kcUIUnFFrM24u96VbCbX09bvfuF6jdYpk0.v/cH/q.Zi'),
    ('Usuario9','usuario9@misitio.cl', '$2y$10$EjRzw5xU7kcUIUnFFrM24u96VbCbX09bvfuF6jdYpk0.v/cH/q.Zi'),
    ('Usuario10','usuario10@misitio.cl', '$2y$10$EjRzw5xU7kcUIUnFFrM24u96VbCbX09bvfuF6jdYpk0.v/cH/q.Zi');

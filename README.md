# Mur d'Imatges

Aplicació web inspirada en Pinterest, desenvolupada en PHP amb arquitectura MVC. Permet explorar imatges paginades, fer cerques en temps real, gestionar usuaris amb autenticació JWT i donar likes a les imatges preferides.

---

## Funcionalitats

- **Mur d'imatges** amb càrrega progressiva (infinite scroll, 20 imatges per lot)
- **Cerca en temps real** per títol i per paraules clau / categoria
- **Autenticació** mitjançant JWT emmagatzemat en cookie (1 hora de validesa)
- **Likes per usuari** — cada usuari només pot donar like una vegada per imatge
- **Perfil d'usuari** amb historial de les imatges marcades com a favorites
- **Vista de detall** d'imatge individual

---

## Tecnologies

| Capa | Tecnologia |
|---|---|
| Backend | PHP 8+ |
| Base de dades | SQLite3 |
| Autenticació | JWT manual (HS256) |
| Frontend | HTML + CSS + JavaScript |

---

## Estructura del projecte

```
/
├── index.php                        # Pàgina principal (mur d'imatges)
├── api/
│   ├── imatgesApi.php               # API REST: GET / PATCH imatges i likes
│   └── cercaVol.php                 # API de cerca (GET per títol, POST per categoria)
├── controller/
│   ├── auth.php                     # Validació del token JWT
│   ├── login.proc.php               # Procés d'inici de sessió
│   ├── logout.proc.php              # Tancament de sessió
│   └── registre.proc.php            # Registre d'usuaris
├── dao/
│   └── imatgesDao.php               # Accés a dades: consultes SQLite
├── dataBase/
│   ├── imatges.db                   # Base de dades SQLite
│   ├── databaseInit.php             # Inicialització i seed de dades
│   └── dataBaseInitRelacional.php   # Creació de la taula `likes`
├── view/
│   ├── imatges.php                  # Vista de detall d'una imatge
│   ├── perfilUsuari.php             # Perfil i historial de likes
│   ├── login.php                    # Formulari d'inici de sessió
│   └── registre.php                 # Formulari de registre
├── includes/
│   ├── db_connect.php               # Connexió a la base de dades
│   ├── check_auth.php               # Guard per a rutes protegides
│   ├── header.php                   # Capçalera comuna
│   ├── head.html                    # `<head>` HTML comú
│   ├── footer.html                  # Peu de pàgina comú
│   └── css/styles.css               # Estils globals
└── script/
    ├── login.js                     # Lògica JS del formulari de login
    └── registre.js                  # Lògica JS del formulari de registre
```

---

## Base de dades

Dues taules principals:

**`imatges`** — conté les imatges amb `id`, `img_url`, `img_titol`, `par_clau` (paraules clau) i `num_likes`.

**`likes`** — registra quins usuaris han donat like a quines imatges. La constraint `UNIQUE(id_imatge, usuari)` impedeix duplicats.

---

## API endpoints

### `GET /api/imatgesApi.php`

| Paràmetre | Descripció |
|---|---|
| `?offset=N&limit=N` | Llista d'imatges paginada |
| `?id=N` | Detall d'una imatge per ID |
| `?username=NOM` | Imatges a les quals l'usuari ha donat like |

### `PATCH /api/imatgesApi.php`

Dona like a una imatge. Requereix cos JSON `{ "id": N, "username": "nom" }` i usuari autenticat.

### `GET /api/cercaVol.php?busqueda=text`

Retorna fins a 5 suggeriments de títols (autocomplete).

### `POST /api/cercaVol.php`

Filtra imatges per categoria/paraula clau. Cos JSON `{ "cerca": "categoria" }`.

---

## Instal·lació

**Requisits:** PHP 8+ amb les extensions `sqlite3` i `json` habilitades.

```bash
# Clonar el repositori
git clone <url-del-repo>
cd 0613-a19-p-interest-xavi_adan

# Inicialitzar la base de dades (primera vegada)
php dataBase/databaseInit.php
php dataBase/dataBaseInitRelacional.php

# Iniciar el servidor de desenvolupament
php -S localhost:8000
```

Accedeix a `http://localhost:8000` al navegador.

---

## Notes de seguretat

> Aquest projecte és un exercici acadèmic. Abans de desplegar en producció caldria:
> - Substituir la clau secreta JWT (`"clauSuperSecreta123"`) per una variable d'entorn
> - Canviar el hash de contrasenyes de MD5 a `password_hash()` / `password_verify()`
> - Afegir la flag `HttpOnly` i `Secure` a la cookie del token

---

## Autor

**Xavi - Adan** — Projecte de classe

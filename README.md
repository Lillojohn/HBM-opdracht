    <p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

# HBM-opdracht

### PHP Laravel assessment
Deze code assessment is bedoeld om een beeld te krijgen van je skills als developer bij het
maken van een simpele API voor een takenlijst applicatie. Bij deze code assessment gebruik je
Laravel en maak je de API endpoints beschikbaar.
We verwachten nette, gestructureerde code voorzien van comments. De API endpoints moeten
kunnen worden getest met bijvoorbeeld Postman.
Hoe je de benodigde models maakt om de applicatie werkend te krijgen is geheel aan jou. Als
database backend kun je MySQL of PostgreSQL gebruiken.
Je code sla je op in git, HBM maakt gebruik van Gitlab maar je bent vrij om je eigen git
repository te kiezen. Gebruik duidelijke commit messages en eventuele Merge Requests met
een duidelijke omschrijving.

#### Registratie:
- Registreren met email, password en password confirmation als POST body
- Een API route maakt een gebruiker die nog niet is geverifieerd aan in de database
- Een API route stuurt een verificatie mail naar het email adres van de gebruiker
- Geeft een duidelijke respons terug aan de gebruiker
####  Verificatie:
- Verifieer de gebruiker aan de hand van de verificatie code die aan de gebruiker is
  verstuurd
####  Login:
- Inloggen met email adres en wachtwoord, van geverifieerde gebruiker
- Maak een tijdelijke sessie aan, hetzij tot logout door gebruiker, hetzij na automatische
  logout na timeout
- Toon een duidelijke respons als een gebruiker (nog) niet is geverifieerd
####  Takenlijst:
- Gebruikers kunnen alleen hun eigen takenlijst zien
- Laat maximaal 10 items per pagina zien, bij voorkeur door gebruik te maken van
  Laravel’s ingebouwde pagination
- Implementeer logica om te kunnen zoeken op taken in de lijst
####  Taak aanmaken:
- 2 verplichte velden: titel en beschrijving
- Bij succesvol aanmaken sla je de taak op in de database
- Geef een duidelijke respons terug aan de gebruiker
####  Taak bekijken:
- Geef een object terug met de titel en beschrijving van de opgevraagde taak
####  Taak updaten:
- 2 verplichte velden: titel en beschrijving
- Bij succesvol updaten sla je de taak op in de database
- Geef een duidelijke respons terug aan de gebruiker
####  Taak verwijderen:
- Verwijder de taak uit de database
- Geef een duidelijke respons terug aan de gebruiker

------------------------------------------------------------------------------------------

# Uitwerking

## Installatie

Het project draait op een serverless vercel omgeving. 

Om het project lokaal te draaien, moet je eerst de volgende stappen uitvoeren:

```git clone https://github.com/Lillojohn/HBM-opdracht.git```

```cd HBM-opdracht```

``` composer install```

``` php artisan serve```

```mv .env.example .env```

Hierna kun je de applicatie benaderen via http://127.0.0.1:8000

### Database

De database is een MySQL database. De database configuratie staat in het .env bestand. De database naam is hbm_opdracht. De database moet eerst worden aangemaakt. Hierna kun je de database migreren.


### Mail

Voor de mail functionaliteit is er gebruik gemaakt van Mailtrap. De mailtrap configuratie staat in het .env bestand.

------------------------------------------------------------------------------------------
## Design 

De applicate is gebouwd met de gedachte dat het draait op een serverless omgeving van vercel. 
Deze omgeving is te vinden op https://hbm-opdracht.vercel.app/.

De kan applicatie kan ook lokaal gedraaid worden met  het commando ```php artisan serve```. 
Om dit werkend te krijgen moeten de database en mail configuratie worden aangepast in het .env bestand.

De applicatie werkt vanuit de browser, maar ook via de API. De API is te benaderen via de endpoints beschreven in de API sectie.

Voor de authorizatie is gekozen om te werken met een tijdelijke cookie, die gelinkt is aan een sessie token. 
Deze token wordt opgeslagen in de database. De token wordt gebruikt om de gebruiker te identificeren.

------------------------------------------------------------------------------------------

## API

De API is te benaderen via de volgende endpoints:

### Registratie

<details>
 <summary><code>POST</code> <code><b>/api/user/register</b></code> <code>(Registreer een nieuwe gebruiker)</code></summary>

##### Parameters

> | name      |  type     | data type     | description              |
> |-----------|---------------|--------------------------|-----------------------------------------------------------------------|
> | name      |  required | string        | Naam van de gebruiker    |
> | password      |  required | string        | Wachtwoord van gebruiker |
> | email      |  required | string(email) | Email van gebruiker      |

</details>

------------------------------------------------------------------------------------------

### Login:

<details>
 <summary><code>POST</code> <code><b>/api/user/login</b></code> <code>(Log de gebruiker in)</code></summary>

##### Parameters

> | name      |  type     | data type     | description              |
> |-----------|---------------|--------------------------|-----------------------------------------------------------------------|
> | password      |  required | string        | Wachtwoord van gebruiker |
> | email      |  required | string(email) | Email van gebruiker      |

</details>

------------------------------------------------------------------------------------------


### Taak:

#### Taak aanmaken

<details>
 <summary><code>POST</code> <code><b>/api/task</b></code> <code>(Maakt een nieuwe taak aan)</code></summary>

##### Parameters

> | name       |  type     | data type | description               |
> |-----------|---------------|--------------------------|-----------------------------------------------------------------------|
>| name       |  required | string    | Naam van de taak         |
> | description          |  required | string    | Omschrijving van de taak |
> | taskListId |  required | integer   | Id van de takenlijst     |

</details>

#### Taak bekijken

<details>
 <summary><code>GET</code> <code><b>/api/task/{id}</b></code> <code>(Bekijk een taak)</code></summary>

##### Parameters

> | name       |  type     | data type | description               |
> |-----------|---------------|--------------------------|-----------------------------------------------------------------------|
> | id       |  required | string    | id van een taak      |

</details>

#### Taak aanpassen

<details>
 <summary><code>POST</code> <code><b>/api/task/update</b></code> <code>(Wijzig een bestaande taak)</code></summary>

##### Parameters

> | name       |  type     | data type | description               |
> |-----------|---------------|--------------------------|-----------------------------------------------------------------------|
>| id       |  required | string    | id van een taak      |
> | name       |  required | string    | Naam van de taak         |
> | description          |  required | string    | Omschrijving van de taak |

</details>

#### Taak verwijderen

<details>
 <summary><code>POST</code> <code><b>/api/task/delete</b></code> <code>(Verwijder een taak op Id)</code></summary>

##### Parameters

> | name       |  type     | data type | description                 |
> |-----------|---------------|--------------------------|-----------------------------------------------------------------------|
> | id       |  required | string    | id van een taak      |

</details>

#### Taak Zoeken

<details>
 <summary><code>POST</code> <code><b>/api/task/search</b></code> <code>(Zoek een taak met een zoekterm. Als de zoekterm niet past in de taaknaam word de taak uitgefilterd.)</code></summary>

##### Parameters

> | name       |  type     | data type | description                |
> |-----------|---------------|--------------------------|-----------------------------------------------------------------------|
> | search       |  required | string    | Zoekterm dat gebruikt word om de taken te filteren        |

</details>

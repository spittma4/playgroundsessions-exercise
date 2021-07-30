## Development Environment Setup

The following instructions are useful,
even if you want to use some other development environment. 

### Instructions

1. Inside a blank directory, download
    a [https://github.com/PlaygroundSessions/php-code-exercise/zipball/main/](zip file of the code)
    for this exercise, and unzip it.  Don't clone the project.

1. Install composer packages
    ```
    php composer.phar install
    ```
    You may need to install some required php extensions.

1. Serve the project locally, using the built-in PHP development server.
    ```
    php -S localhost:8000 -t public
    ```
   
1. You should now see the text `Lumen (8.2.1) (Laravel Components ^8.0)` at [http://localhost:8000](http://localhost:8000)

1. Initialize a git repository, and create an initial commit.

1. It should take about 2 seconds to load [http://localhost:8000/student-progress/1](http://localhost:8000/student-progress/1)

1. Your development environment is all set up!

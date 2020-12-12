/*
Primero, importamos todos los módulos de npm que queremos utilizar.
Recordemos que npm trabaja de manera súper modular. Cada acción que
queremos realizar, requiere su propio módulo.

require() importa un módulo. Recibe como parámetro el nombre
del mismo.

El nombre de variable que usemos es indistinto.
*/
var gulp = require('gulp');
var gutil = require('gulp-util');
var bower = require('bower');
var concat = require('gulp-concat');
//var sass = require('gulp-sass');
var minifyCss = require('gulp-minify-css');
var rename = require('gulp-rename');
var sh = require('shelljs');

// Requerimos nuestro paquete de gulp-sourcemaps que descargamos.
var sourcemaps = require('gulp-sourcemaps');

// Ionic agrega una variable para guardar las rutas de las distintas
// fuentes que utiliza. Por defecto, solo trae la de SASS.
var paths = {
  sass: ['./scss/**/*.scss']
};

/*
gulp define solo 4 métodos:
- task
  Crea una tarea que luego podemos ejecutar desde la terminal.
  Recibe 2 parámetros.
  1. El nombre de la tarea.
  2. Una función con lo que la tarea debe realizar.
    O puede recibir un array con una serie de tareas a ejecutar primero.
- src
  Permite leer un stream de datos de archivos de texto a memoria.
  Todas las acciones que realiza gulp, las realiza en _memoria_.
  Hasta que no se lo indiquemos, gulp _no_ graba nada en disco.
- dest
  Indica que debe grabar en disco el stream actual.
- watch
  Este comando le indica a gulp que "observe" uno o más archivos
  para detectar cambios. Y que cuando encuentre un cambio, ejecute
  tal o cual tarea.
  IMPORTANTE: El watch de gulp _no_ checkea si se crean archivos 
  nuevos. Solo checkea cambios en archivos existentes al empezar.
  Por eso, si se agrega algún js nuevo tenemos que usar Ctrl+C en la consola
  para detener el watch cuando se está ejecutando, y después volver a
  ejecutar el watch.
*/

gulp.task('default', ['sass']);

// Creamos nuestra tarea para unificar todos los JavaScripts en un
// único archivo.
// Una vez creada, podemos ir a la terminal, y dentro de la carpeta
// de este archivo, llamar a "gulp js", donde "js" es el nombre
// de esta tarea.
gulp.task('js', function() {
  // Primero, tenemos que decirle a gulp que lea todos los archivos
  // de js que queremos.
  // Para leer archivos a memoria, usamos el comando src.
  // Una vez levantados en memoria, la idea es vamos al resultado
  // de cada comando, mandarlo via "tubería" o "pipe" al siguiente
  // comando. Para eso, usamos el método "pipe".
  gulp.src(['./www/js/*.js', './www/js/**/*.js'])

    // Antes de empezar a manipular los archivos, agregamos el sourcemaps.
    .pipe(sourcemaps.init())

    // Unimos todos los archivos a uno único con el nombre bundle.js
    .pipe(concat('bundle.js'))

    // Antes de grabar, agregamos la escriuta del sourcemaps.
    //.pipe(sourcemaps.write())
    // Opcionalmente, podemos grabar el sourcemaps en un archivo externo, para
    // sumar un peso mínimo al bundle.
    // Para eso, solo le pasamos una ruta de a donde escribir este archivo al
    // write().
    .pipe(sourcemaps.write('./../maps'))
    // Generalmente, en una web, uno prefiere que el sourcemaps sea externo.
    // Dicho esto, en una APP híbrida, que vamos a compilar y después depurar
    // en el mismo dispositivo, por cómo el wrapper de Cordova funciona, no
    // puede leer el sourcemaps externo. Por lo cual, en ese caso, debemos usar
    // el inline (primera variante).

    // Como todo fue en memoria, vamos a decirle ahora que lo grabe
    // a disco, usando gulp.dest().
    .pipe(gulp.dest('./www/dist/'));
});

// Definimos una tarea para "observar" cambios en los JavaScript,
// para llamar a nuestra tarea "js" que junte todo.
// Para evitar posibles problemas, no está de más decirle a esta
// tarea que primero, antes de empezar a "observar", ejecute de una
// la tarea "js". El segundo parámetro es un array con las tareas
// que queremos que ejecute antes de la propia.
gulp.task('js:watch', ['js'], function() {
  // gulp.watch recibe 2 parámetros.
  // 1. La lista de los archivos que debe observar (todos los js
  //  en este caso).
  // 2. La lista de tasks que debe ejecutar cuando detecte un cambio.
  //  (la tarea 'js' en nuestro caso).
  gulp.watch(['./www/js/*.js', './www/js/**/*.js'], ['js']);
});

gulp.task('sass', function(done) {
  gulp.src('./scss/ionic.app.scss')
    .pipe(sass())
    .on('error', sass.logError)
    .pipe(gulp.dest('./www/css/'))
    .pipe(minifyCss({
      keepSpecialComments: 0
    }))
    .pipe(rename({ extname: '.min.css' }))
    .pipe(gulp.dest('./www/css/'))
    .on('end', done);
});

gulp.task('watch', ['sass'], function() {
  gulp.watch(paths.sass, ['sass']);
});

gulp.task('install', ['git-check'], function() {
  return bower.commands.install()
    .on('log', function(data) {
      gutil.log('bower', gutil.colors.cyan(data.id), data.message);
    });
});

gulp.task('git-check', function(done) {
  if (!sh.which('git')) {
    console.log(
      '  ' + gutil.colors.red('Git is not installed.'),
      '\n  Git, the version control system, is required to download Ionic.',
      '\n  Download git here:', gutil.colors.cyan('http://git-scm.com/downloads') + '.',
      '\n  Once git is installed, run \'' + gutil.colors.cyan('gulp install') + '\' again.'
    );
    process.exit(1);
  }
  done();
});

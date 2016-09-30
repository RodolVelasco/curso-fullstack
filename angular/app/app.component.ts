// Importar el núcleo de Angular
import {Component} from '@angular/core';
 
// Decorador component, indicamos en que etiqueta se va a cargar la plantilla
@Component({
    selector: 'my-app',
    template: '<h1>Hola mundo con Angular 2 !! victorroblesweb.es</h1>'
})
 
// Clase del componente donde irán los datos y funcionalidades
export class AppComponent { }
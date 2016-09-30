// Importar el núcleo de Angular
import {Component} from '@angular/core';
import { ROUTER_DIRECTIVES, Router, ActivatedRoute } from "@angular/router";

// Decorador component, indicamos en que etiqueta se va a cargar la 
@Component({
    selector: 'my-app',
    template: `<h1>Hola mundo con Angular 2 !! victorroblesweb.es</h1><hr/>
               <router-outlet></router-outlet>
    `,
    directives: [ROUTER_DIRECTIVES]
})
 
// Clase del componente donde irán los datos y funcionalidades
export class AppComponent { }

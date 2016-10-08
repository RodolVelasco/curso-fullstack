// Importar el núcleo de Angular
import {Component} from '@angular/core';
import { ROUTER_DIRECTIVES, Router, ActivatedRoute } from "@angular/router";

// Decorador component, indicamos en que etiqueta se va a cargar la 
@Component({
    selector: 'my-app',
    templateUrl: 'app/view/layout.html',
    directives: [ROUTER_DIRECTIVES]
})
 
// Clase del componente donde irán los datos y funcionalidades
export class AppComponent { }

import { provideRouter, RouterConfig } from '@angular/router';

import { LoginComponent } from "./components/login.component";
import { RegisterComponent } from "./components/register.component";
import { DefaultComponent } from "./components/default.component";

export const routes: RouterConfig = [
    {
        path: '',
        redirectTo: '/index',
        terminal: true
    },
    { path: 'index', component: DefaultComponent },
    { path: 'login', component: LoginComponent },
    { path: 'register', component: RegisterComponent }
];

export const APP_ROUTER_PROVIDERS = [
    provideRouter(routes)
];
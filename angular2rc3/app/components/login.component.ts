import {Component, OnInit} from '@angular/core';
 
// Decorador component, indicamos en que etiqueta se va a cargar la plantilla
@Component({
    selector: 'login',
    templateUrl: 'app/view/login.html'
})
 
export class LoginComponent implements OnInit { 
    public titulo: string = "Identificate";
    public user;
    
    ngOnInit(){
        
        this.user = {
            "email": "",
            "password": "",
            "getHash": "false"
            
        };
        
    }
    
    onSubmit(){
        console.log(this.user);
    }
}
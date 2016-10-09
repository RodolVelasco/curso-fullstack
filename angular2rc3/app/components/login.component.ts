import {Component, OnInit} from '@angular/core';
import {LoginService} from '../services/login.service';
 
// Decorador component, indicamos en que etiqueta se va a cargar la plantilla
@Component({
    selector: 'login',
    templateUrl: 'app/view/login.html',
    providers: [LoginService]
})
 
export class LoginComponent implements OnInit { 
    public titulo: string = "Identificate";
    public user;
    public errorMessage;
    
    constructor(
        private _loginService: LoginService
    ){}
    
    ngOnInit(){
        
        this.user = {
            "email": "",
            "password": "",
            "gethash": "false"
            
        };
        
    }
    
    onSubmit(){
        console.log(this.user);
        this._loginService.signup(this.user).subscribe(
                response => {
                    //alert(response);
                    console.log(response);
                },
                error => {
                    this.errorMessage = <any>error;
                    
                    if(this.errorMessage != null){
                        console.log(this.errorMessage);
                        alert("Error en la petición");
                    }
                }
            );
    }
}
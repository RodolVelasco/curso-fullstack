import { platformBrowserDynamic } from '@angular/platform-browser-dynamic';
import { AppModule } from './app.module';
import { APP_ROUTER_PROVIDERS } from './app.routes';
platformBrowserDynamic().bootstrapModule(AppModule);
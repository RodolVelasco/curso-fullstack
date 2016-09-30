"use strict";
var platform_browser_dynamic_1 = require('@angular/platform-browser-dynamic');
var app_component_1 = require('./app.component');
var app_routes_1 = require('./app.routes');
//bootstrap(AppComponent);
platform_browser_dynamic_1.bootstrap(app_component_1.AppComponent, [app_routes_1.APP_ROUTER_PROVIDERS]).catch(function (err) { return console.log(err); });
//# sourceMappingURL=main.js.map
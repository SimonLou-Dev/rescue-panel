/**
* First we will load all of this project's JavaScript dependencies which
* includes React and other helpers. It's a great starting point while
* building robust, powerful web applications using React + Laravel.
*/


import Login from "./components/Login";

/**
 * Next, we will create a fresh React component instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */


import React from 'react';
import ReactDOM from 'react-dom';
import '../../public/css/app.css';
import * as Sentry from "@sentry/react";
import { Integrations } from "@sentry/tracing";
import PreApp from "./components/PreApp";




Sentry.init({
        dsn: "https://4ef83bdc75054cc88ab4d44ef8c749d7@o1059354.ingest.sentry.io/6047890",
        integrations: [new Integrations.BrowserTracing()],
        // Set tracesSampleRate to 1.0 to capture 100%
        // of transactions for performance monitoring.
        // We recommend adjusting this value in production
        tracesSampleRate: 1.0,
    });




function App(){
    return (
        <Sentry.ErrorBoundary>
            <PreApp/>
        </Sentry.ErrorBoundary>
    )
}
export default App;

if (document.getElementById('app')) {
    ReactDOM.render(<App/>, document.getElementById('app'));
}

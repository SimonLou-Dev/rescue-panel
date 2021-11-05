/**
* First we will load all of this project's JavaScript dependencies which
* includes React and other helpers. It's a great starting point while
* building robust, powerful web applications using React + Laravel.
*/

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
import NotificationsProvider from "./components/context/NotificationProvider";
import {BrowserRouter, Route, Switch} from "react-router-dom";
import Login from "./components/Login";
import Register from "./components/Register";
import Mdpreset from "./components/Mdpreset";
import Emailsender from "./components/Emailsender";
import Error from "./components/Error";
import Maintenance from "./components/Maintenance";
import GetInfos from "./components/GetInfos";
import Layout from "./components/Layout";

Sentry.init({
        dsn: "https://4ef83bdc75054cc88ab4d44ef8c749d7@o1059354.ingest.sentry.io/6047890",
        integrations: [new Integrations.BrowserTracing()],
        // Set tracesSampleRate to 1.0 to capture 100%
        // of transactions for performance monitoring.
        // We recommend adjusting this value in production
        tracesSampleRate: 1.0,
});

class App extends React.Component{
    constructor(props) {
        super(props);

    }

    render() {
        return(
            <Sentry.ErrorBoundary showDialog>
                <NotificationsProvider>
                    <BrowserRouter>
                        <Switch>
                            <Route path='/login' component={Login}/>
                            <Route path='/register' component={Register}/>
                            <Route path='/reset/*' component={Mdpreset}/>
                            <Route path='/sendmail' component={Emailsender}/>
                            <Route path='/ANA' component={Error}/>
                            <Route path='/maintenance' component={Maintenance}/>
                            <Route path='/informations' component={GetInfos}/>
                            <Layout />
                        </Switch>
                    </BrowserRouter>
                </NotificationsProvider>
            </Sentry.ErrorBoundary>
        )
    }


}
export default Sentry.withProfiler(App);

if (document.getElementById('app')) {
    ReactDOM.render(<App/>, document.getElementById('app'));
}

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
import NotificationsProvider from "./components/context/NotificationProvider";
import {BrowserRouter, Route, Switch} from "react-router-dom";
import Login from "./components/AuthComponent/Login";
import Register from "./components/AuthComponent/Register";
import Maintenance from "./components/Maintenance";
import GetInfos from "./components/AuthComponent/GetInfos";
import Cantaccess from "./components/AuthComponent/Cantaccess";
import Layout from "./components/App/Layout";

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
                            <Route path='/cantaccess' component={Cantaccess}/>
                            <Route path='/maintenance' component={Maintenance}/>
                            <Route path='/informations' component={GetInfos}/>
                            <Layout/>
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

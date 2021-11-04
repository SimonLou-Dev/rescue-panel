import React from 'react';
import NotificationsProvider from "./context/NotificationProvider";
import {BrowserRouter, Route, Switch} from "react-router-dom";
import Login from "./Login";
import Register from "./Register";
import Mdpreset from "./Mdpreset";
import Emailsender from "./Emailsender";
import Error from "./Error";
import Maintenance from "./Maintenance";
import GetInfos from "./GetInfos";
import Layout from "./Layout";
import * as Sentry from "@sentry/react";


class PreApp extends React.Component{
    constructor(props) {
        super(props);

    }


    render() {
        return (
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
        );
    }
}
export default Sentry.withProfiler(PreApp);



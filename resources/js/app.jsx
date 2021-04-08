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
import {BrowserRouter, Route, Switch} from 'react-router-dom'
import Layout, {NewLayout} from "./components/Layout";
import '../../public/css/app.css';
import Register from "./components/Register";
import Mdpreset from './components/Mdpreset';
import Error from './components/Error';
import Emailsender from './components/Emailsender';
import Maintenance from "./components/Maintenance";
import GetInfos from "./components/GetInfos";
import NotificationsProvider from "./components/context/NotificationProvider";

class App extends React.Component{
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
                        <NewLayout />
                    </Switch>
                </BrowserRouter>
            </NotificationsProvider>
        );
    }
}
export default App;
if (document.getElementById('app')) {
    ReactDOM.render(<App/>, document.getElementById('app'));
}

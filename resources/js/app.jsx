/**
* First we will load all of this project's JavaScript dependencies which
* includes React and other helpers. It's a great starting point while
* building robust, powerful web applications using React + Laravel.
*/


import Login from "./components/Login";

require('./bootstrap');

/**
 * Next, we will create a fresh React component instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */


import React from 'react';
import ReactDOM from 'react-dom';
import {BrowserRouter, Route, Switch} from 'react-router-dom'
import Layout from "./components/Layout";
import '../../public/css/app.css';
import Register from "./components/Register";
import Mdpreset from './components/Mdpreset';
import Error from './components/Error';
import Emailsender from './components/Emailsender';
import NotifSucces from './components/props/notifs/Notifs';
import Notifications from "./components/props/utils/Notifications";
var notifs = true;

class App extends React.Component{
    constructor(props) {
        super(props);

    }




    render() {
        return (
            <BrowserRouter>
                <Switch>
                    <Route path='/login' component={Login}/>
                    <Route path='/register' component={Register}/>
                    <Route path='/reset/*' component={Mdpreset}/>
                    <Route path='/sendmail' component={Emailsender}/>
                    <Route path='/ANA' component={Error}/>
                    <Layout />
                </Switch>
                <Notifications/>
            </BrowserRouter>
        );
    }
}
export default App;
if (document.getElementById('app')) {
    ReactDOM.render(<App/>, document.getElementById('app'));
}

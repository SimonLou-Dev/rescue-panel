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
import {BrowserRouter, BrowserRouter as Router, Link, NavLink, Route, Switch} from 'react-router-dom'
import Layout from "./components/Layout";
import '../../public/css/app.css';
import Register from "./components/Register";
import Main from "./components/Main";

class App extends React.Component{
    render() {
        const pathname = window.location.pathname;
        var state = 0;
        if(pathname === "/register"){
            state = 1;
        }
        if(pathname === "/login"){
            state = 2;
        }
        if(pathname !== "/register" && pathname!== "/login"){
            state = 3;
        }

        return (
            <BrowserRouter>
                <Switch>
                    <Route path='/login' component={Login}/>
                    <Route path='/register' component={Register}/>
                    <Layout />
                </Switch>
            </BrowserRouter>
        );
    }
}
export default App;
if (document.getElementById('app')) {
    ReactDOM.render(<App/>, document.getElementById('app'));
}



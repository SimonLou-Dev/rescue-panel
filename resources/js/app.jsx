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
var notifs = true;

class App extends React.Component{
    constructor(props) {
        super(props);
        this.state = {
            notif: false,
            notifs: []

        }
        this.removenotif = this.removenotif.bind(this)
    }


    async removenotif(id){
            notifs = this.state.notifs;
            notifs.splice(id, 1);
            var a = 0;
            notifs.forEach(notif => {
                    notif.id = a
                    a++
                }
            )
            this.setState({notifs: notifs});
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
                {this.state.notif &&
                <div className={'notifs'}>
                    {this.state.notifs.map((notif) =>
                        <NotifSucces remove={this.removenotif} key={notif.id} id={notif.id} type={notif.type} raison={notif.raison}/>
                    )}
                </div>
                }
            </BrowserRouter>
        );
    }
}
export default App;
if (document.getElementById('app')) {
    ReactDOM.render(<App/>, document.getElementById('app'));
}

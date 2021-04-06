import React from 'react';
import axios from "axios";
import NotifSucces from "../notifs/Notifs";
var table = [];


function notiifate(test){
    console.log('test')
}

class Notifications extends React.Component {
    constructor(props) {
        super(props);
        this.state = {
            notif: false,
            notifs: table,
            mounted: false,
            style: 'hidden',
            channel: null,
        }
        //this.removenotif = this.removenotif.bind(this)
        this.addnotifs = this.addnotifs.bind(this)

        }
    /*removenotif(id){
        const notifs = this.state.notifs;
        notifs.splice(id, 1);
        let a = 0;
        notifs.forEach(notif => {
                notif.id = a
                a++
            })
        const bool = notifs.length > 0;
        this.setState({notifs: notifs, notif: bool});
    }*/


    addnotifs(type, raison){




    }



    componentDidMount() {
        // Enable pusher logging - don't include this in production


        var pusher = new Pusher('fd78f74e8faecbd2405b', {
            cluster: 'eu'
        });


        var channel = pusher.subscribe('my-channel');
        channel.bind('notify', function(data) {
            const notifs = table;
            const len = notifs.length;
            var id;
            if(len > 0){
                id = len;
            }else{
                id = 0;
            }

            notifs.push({
                id: id,
                type: data.type,
                raison: data.text,
            })
            table = notifs;
            new Notifications().update();
        });

        console.log('test')

        const notifs = this.state.notifs;
        console.log(table);
        const len = notifs.length;
        let style = this.state.notif? 'fixed':'hidden';

    }

    render() {
        return (
            this.state.notif &&
            <div className={'notifs'}>
                {this.state.notifs.map((notif) =>
                    <NotifSucces remove={this.removenotif} key={notif.id} id={notif.id} type={notif.type} raison={notif.raison}/>
                )}
            </div>
        )
    }
}

export default Notifications;

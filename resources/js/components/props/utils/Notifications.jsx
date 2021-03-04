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
            notif: true,
            notifs: table,
            mounted: false,
        }
        this.removenotif = this.removenotif.bind(this)
        this.addnotifs = this.addnotifs.bind(this)

        }
    removenotif(id){
        var notifs = this.state.notifs;
        notifs.splice(id, 1);
        var a = 0;
        notifs.forEach(notif => {
                notif.id = a
                a++
            }
        )
        this.setState({notifs: notifs});
    }
    addnotifs(type, raison){
        const notifs = this.state.notifs;
        const len = notifs.length;
        var id;
        if(len > 0){
            id = len;
        }else{
            id = 0;
        }

        notifs.push({
            id: id,
            type: type,
            raison: raison,
        })
        this.setState({notif:true, notifs: notifs});

    }

    render() {
        return (
            <div className={'notifs'} style={{position: this.state.notif? 'fixed':'hidden'}}>
                {this.state.notifs.map((notif) =>
                    <NotifSucces remove={this.removenotif} key={notif.id} id={notif.id} type={notif.type} raison={notif.raison}/>
                )}
            </div>
        )
    }
}

export default Notifications;

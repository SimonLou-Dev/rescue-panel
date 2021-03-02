import React from 'react';
import ReactDOM from 'react-dom';
import { Route, Switch } from 'react-router-dom'
import AnnonceCard from "./props/Main/AnnonceCard";
import PersonnelList from "./props/Main/PersonnelList";
import axios from "axios";




class Main extends React.Component {
    constructor(props) {
        super(props);
        this.state = {annonces: [], data:false};
    }

    async componentDidMount() {
        this.hasdata(false);
        var req = await axios({
            url: '/data/annonces',
            method: 'GET'
        });
        this.setState({annonces: req.data.annonces});
        this.hasdata(true);
    }

    hasdata(bool){
        this.setState({data:bool})
    }

    render() {
        return (
            <div id={"Main-Page"}>
                <PersonnelList/>
                <div className={'Annonces'}>
                    <h1>Annonces : </h1>
                    <div className={'Annonces-List'}>
                        {!this.state.data &&
                        <div className={'load'}>
                            <img src={'/assets/images/loading.svg'} alt={''}/>
                        </div>
                        }
                        {this.state.data &&
                         this.state.annonces.map((annonce) =>
                             <AnnonceCard title={annonce.title} key={annonce.id} content={annonce.content} date={annonce.posted_at}/>
                         )
                        }
                    </div>
                </div>
            </div>


        );
    }
}
export default Main;


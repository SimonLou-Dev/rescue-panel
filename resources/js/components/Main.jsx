import React from 'react';
import AnnonceCard from "./props/Main/AnnonceCard";
import PersonnelList from "./props/Main/PersonnelList";
import axios from "axios";
import dateFormat from "dateformat";
import {ListeServicePersonnel} from "./props/Main/ListeServicePersonnel";




class Main extends React.Component {
    constructor(props) {
        super(props);
        this.state = {annonces: [], data:false, text: ''};
    }

    async componentDidMount() {
        this.hasdata(false);
        var req = await axios({
            url: '/data/annonces',
            method: 'GET'
        });
        await axios({
            method: 'GET',
            url: '/data/infosutils/get',
        }).then(response => {
            this.setState({text : response.data.infos})
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
                <ListeServicePersonnel/>
                <div className={'rowed'}>
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
                                <AnnonceCard title={annonce.title} key={annonce.id} content={annonce.content} date={dateFormat(annonce.updated_at, 'yyyy/mm/dd ') +  '[FR]'}/>
                            )
                            }
                        </div>
                    </div>
                    <div className={'Links'}>
                        <h1 className={'utilsName'}>Liens utiles</h1>
                        {!this.state.data &&
                        <div className={'load'}>
                            <img src={'/assets/images/loading.svg'} alt={''}/>
                        </div>
                        }

                        {this.state.data &&
                            <div className={'render'} id={'UtilsRendering'} dangerouslySetInnerHTML={{__html:this.state.text}}/>
                        }

                    </div>
                </div>
            </div>


        );
    }
}
export default Main;


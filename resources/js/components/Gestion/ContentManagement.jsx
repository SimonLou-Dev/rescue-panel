import React from 'react';
import axios from "axios";
import PagesTitle from "../props/utils/PagesTitle";
import ContentCard from "../props/Gestion/Content/ContentCard";


class ContentManagement extends React.Component {
    constructor(props) {
        super(props);
        this.state= {content: "", title: ""}
    }

    async submit(e) {
        e.preventDefault();
        if (this.state.content !== "" && this.state.title !== "") {
            await axios({
                method: 'post',
                url: '/data/gestion/content/add/5',
                data: {
                    title: this.state.title,
                    formcontent: this.state.content,
                }
            });
            this.setState({
                content: '',
                title: '',
            });
        }
    }


    render() {
        return (
            <div className={'ContentManagement'}>
                <section className={'header'}>
                    <PagesTitle title={'Gestion de contenu'}/>
                </section>
                <section className={'content-mgt'}>
                    <ContentCard type={1}/>
                    <ContentCard type={2}/>
                    <ContentCard type={3}/>
                    <ContentCard type={4}/>
                    <ContentCard type={5}/>
                    <div className={'ContentCard annonces'}>
                        <h1>Ajouter une annonce</h1>
                        <form method={'POST'} onSubmit={(e) => this.submit(e)}>
                            <section className="left">
                                <input type={'text'} placeholder={'titre'} value={this.state.title} onChange={(e)=> {this.setState({title: e.target.value})}}/>
                                <textarea value={this.state.content} onChange={(e)=> {this.setState({content: e.target.value})}}/>
                            </section>
                            <section className="right">
                                <div className="rowed">
                                    <label>poster sur discord</label>
                                    <div className={'switch-container'}>
                                        <input id={"switch1"} checked={this.state.payed} className="payed_switch" type="checkbox" onChange={event => {this.onchange(event)}}/>
                                        <label htmlFor={"switch1"} className={"payed_switchLabel"}/>
                                    </div>
                                </div>
                                <div className="rowed">
                                    <label>mentioner sur discord</label>
                                    <div className={'switch-container'}>
                                        <input id={"switch2"} checked={this.state.payed} className="payed_switch" type="checkbox" onChange={event => {this.onchange(event)}}/>
                                        <label htmlFor={"switch2"} className={"payed_switchLabel"}/>
                                    </div>
                                </div>
                                <div className="rowed">
                                    <label>ajouter une/des réaction(s)</label>
                                    <div className={'switch-container'}>
                                        <input id={"switch3"} checked={this.state.payed} className="payed_switch" type="checkbox" onChange={event => {this.onchange(event)}}/>
                                        <label htmlFor={"switch3"} className={"payed_switchLabel"}/>
                                    </div>
                                </div>
                            </section>
                            <button type={'submit'} className={'btn'}>Ajouter</button>
                        </form>
                    </div>
                    <ContentCard type={6}/>
                </section>
            </div>
        )
    }
}

export default ContentManagement;

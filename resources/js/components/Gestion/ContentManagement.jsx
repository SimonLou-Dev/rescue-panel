import React from 'react';
import ContentCard, {rootUrl} from "../props/Gestion/Content/ContentCard";
import axios from "axios";
import PagesTitle from "../props/utils/PagesTitle";


class ContentManagement extends React.Component {
    constructor(props) {
        super(props);
        this.state= {content: "", title: ""}
    }

    async submit(e) {
        e.preventDefault();
        if (this.state.content !== "" && this.state.title !== "") {
            var req = await axios({
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
                    <ContentCard type={6}/>
                    <div className={'ContentCard annonces'}>
                        <h1>Ajouter une annonce</h1>
                        <form method={'POST'} onSubmit={(e) => this.submit(e)}>
                            <input type={'text'} placeholder={'titre'} value={this.state.title} onChange={(e)=> {this.setState({title: e.target.value})}}/>
                            <textarea value={this.state.content} onChange={(e)=> {this.setState({content: e.target.value})}}/>
                            <button type={'submit'} className={'btn'}>Ajouter</button>
                        </form>
                    </div>
                </section>
            </div>
        )
    };
}

export default ContentManagement;

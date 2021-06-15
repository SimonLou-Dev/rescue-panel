import React from 'react';
import axios from "axios";
import PagesTitle from "../props/utils/PagesTitle";
import ContentCard from "../props/Gestion/Content/ContentCard";
import PermsContext from "../context/PermsContext";
import Rapport from "../Patient/rapport";


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
        let perm = this.context;
        return (
            <div className={'ContentManagement'}>
                <section className={'header'}>
                    <PagesTitle title={'Gestion de contenu'}/>
                </section>
                <section className={'content-mgt'}>
                    {perm.content_mgt ===1  && <ContentCard type={1}/>}
                    {perm.content_mgt ===1&& <ContentCard type={2}/>}
                    {perm.content_mgt ===1&& <ContentCard type={3}/>}
                    {perm.content_mgt ===1&& <ContentCard type={4}/>}
                    {perm.post_annonces ===1&& <ContentCard type={5}/>}
                    {perm.content_mgt ===1 && <ContentCard type={6}/>}
                    {perm.content_mgt ===1&& <ContentCard type={7}/>}
                    {perm.content_mgt ===1&& <ContentCard type={8}/>}
                    {perm.content_mgt ===1&& <ContentCard type={9}/>}
                    {perm.post_annonces ===1&&
                        <div className={'ContentCard annonces'}>
                        <h1>Ajouter une annonce</h1>
                        <form method={'POST'} onSubmit={(e) => this.submit(e)}>
                            <section className="left">
                                <input type={'text'} placeholder={'titre'} value={this.state.title} onChange={(e)=> {this.setState({title: e.target.value})}}/>
                                <textarea value={this.state.content} onChange={(e)=> {this.setState({content: e.target.value})}}/>
                            </section>
                            <button type={'submit'} className={'btn'}>Ajouter</button>
                        </form>
                    </div>
                    }
                </section>
            </div>
        )
    }
}
ContentManagement.contextType = PermsContext;

export default ContentManagement;

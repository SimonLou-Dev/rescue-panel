import React, {useState, useEffect, Fragment ,useMemo,useRef, useCallback, useContext} from 'react';
import { v4 } from 'uuid';
import ChunkedUploady, {
    useItemStartListener,
    useItemFinishListener,
    UploadyContext,
    useItemProgressListener, useFileInput
} from "@rpldy/chunked-uploady";



const CustomButton = (props) => {
    const uploady = useContext(UploadyContext);
    const hanldeUpload = useCallback(()=> {
        uploady.showFileUpload();
    },[uploady]);
    return <button onClick={hanldeUpload} type="primary" disabled={props.disabled}>Custom Upload Button</button>
}

export default function Uploader(){
    const [file, useFile] = useState(null);
    const [FileId, setFileId] = useState(v4());
    const [disabled, setDisabled] = useState(false);
    const [imgUrl, setImgUrl] = useState(null);

    const UploadFinished = () => {
        //Call for say end of Upload
        //Check if props.setFile and if exist setFile dir
        //Return File imgUrl
        setDisabled(false);

    }

    const FileAdded= () => {
        //Call to delete last File
        setDisabled(true)
        setFileId(v4);
    }

    return (
        <div className={'uploader--container'}>
            <ChunkedUploady
                showFileUpload={true}
                chunkSize={100000}
                sendWithFormData={true}
                retries={2}
                chunked={true}
                params={{id:FileId}}
                customInput={true}
                inputFieldName={'file'}
                destination={{ url: "/data/tempupload", headers: {'X-CSRF-TOKEN':csrf} }}
            >
                <div className={'Uploader'}>
                    <CustomButton disabled={disabled}/>
                    <CustomFrom disabled={disabled} fileadded={FileAdded}/>
                    <UploadProgress uploadfinished={UploadFinished}/>
                </div>
            </ChunkedUploady>
        </div>

    )

}

const UploadProgress = (props) => {
    const [progress, setProgess] = useState(0);

    const progressData = useItemProgressListener();

    if(progressData && progressData.completed === 100){
        props.uploadfinished();
    }

    if (progressData && progressData.completed > progress) {
        setProgess(() => progressData.completed);
    }
    return (
        progressData && (
            <div style={{width: progress + 'px'}}/>
        )
    );
};

const CustomFrom = (props) => {
    const inputRef = useRef();
    useFileInput(inputRef);

    return (
        <input type="file" name="testFile" style={{ display: "none" }} ref={inputRef} disabled={props.disabled} onChange={props.fileadded}/>
    )
}




/*

<FileUploader name={"file"}
                          accept="image/*"
                          uploadUrl="/data/tempupload"
                          uploadHeaders={{
                              'X-CSRF-Token': csrf
                          }}
                          chunkSize={100000}
                          onUploadStarted={onUploadStarted}
                          onProgress={onUploadProgress} />
 */

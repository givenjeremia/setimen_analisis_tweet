from flask import Flask
import snscrape.modules.twitter as sntwitter
import pandas as pd
import numpy as np
import re
import emoji
from imblearn.over_sampling import SMOTE
from sklearn.svm import SVC 
from sklearn.model_selection import train_test_split,KFold, cross_val_predict
from sklearn.metrics import classification_report,accuracy_score,precision_score
from flask import jsonify, request

app = Flask(__name__)

@app.route("/<query>/<awal>/<akhir>/<int:count>")
def Crawling(query,awal,akhir,count):
    tweets_list = []
    params = query + ' since:'+ awal +' until:'+akhir+' lang:id';
    # params =  query + ' since:2021-08-30 until:2023-03-28 lang:id'
    print(params)
    for i,tweet in enumerate(sntwitter.TwitterSearchScraper(str(params)).get_items()):
        print(i);
        if i+1>count:
            break
        print(tweet.user.username)
        tweets_list.append({ 'date':tweet.date,'id' : tweet.id, 'username':  tweet.user.username, 'content': tweet.content})
    # return "<p>Hello, World!</p>"
    return jsonify(tweets_list)


def get_emoji_regexp():
    # mengurutkan emoji berdasarkan panjang karakter
    emojis = sorted(emoji.EMOJI_DATA, key=len, reverse=True)
    pattern = u'(' + u'|'.join(re.escape(u) for u in emojis) + u')'
    return re.compile(pattern)

@app.route("/prepro/<tweet>")
def Prepro(tweet):
    tweet = re.sub(r'^RT[\s]+', '', tweet)
    tweet = re.sub(r'#', '', tweet)
    tweet = re.sub(r'http\S+', '',tweet)
    tweet = re.sub(r'[0-9]+', '', tweet)
    tweet = re.sub(r'\_', " ", tweet)
    tweet = re.sub("\W+"," ", tweet)
    tweet = re.sub(r'\\n', " ", tweet)
    tweet = get_emoji_regexp().sub("", tweet).strip()
    tweet = re.sub(r':', '', tweet)
    return jsonify(tweet)


@app.route("/preproPOST",methods = ['POST'])
def PreproPOST():
    tweet = request.form['tweet']
    tweet = re.sub(r'^RT[\s]+', '', tweet)
    tweet = re.sub(r'#', '', tweet)
    tweet = re.sub(r'http\S+', '',tweet)
    tweet = re.sub(r'[0-9]+', '', tweet)
    tweet = re.sub(r'\_', " ", tweet)
    tweet = re.sub("\W+"," ", tweet)
    tweet = re.sub(r'\\n', " ", tweet)
    tweet = get_emoji_regexp().sub("", tweet).strip()
    tweet = re.sub(r':', '', tweet)
    return jsonify(tweet)

@app.route("/oversampling",methods = ['POST'])
def Oversampling():
    data = request.get_json()
    dataset_x = data['x']
    dataset_y = data['y']
    oversapling = SMOTE()
    X_train_res, y_train_res = oversapling.fit_resample(dataset_x, dataset_y)
    x = []
    x.append(X_train_res)
    return jsonify(
        y=y_train_res,
        x=X_train_res,
    )

@app.route("/report",methods = ['POST'])
def Report():
    data = request.get_json()
    pred = pd.DataFrame()
    pred['y_test'] =data['y_test']
    pred['y_pred'] = data['y_pred']
    return jsonify(
        classification_report(pred['y_test'].values,pred['y_pred'].values,output_dict=True)
    )


@app.route("/klasifikasi",methods = ['POST'])
def Klasifikasi():
    data = request.get_json()
    kernel_ = data['kernel']
    text_ = str(data['text'])
    x= data['x']
    y=data['y']
    single_test = data['new_data']
    X_train, X_test, y_train, y_test = train_test_split(x,y,test_size=0.2)
    best_svr = SVC(kernel=kernel_)
    best_svr.fit(X_train, y_train)
    y_pred= best_svr.predict(np.array(X_test))
    y_pred_single = best_svr.predict(np.array(single_test).reshape(1, -1))
    print(classification_report(y_test,y_pred,output_dict=True))
    print(text_)
    print(y_pred_single[0])
    return jsonify(
        kernel=kernel_,
        text=text_,
        label=str(y_pred_single[0]), 
        report=classification_report(y_test,y_pred,output_dict=True)
    )


  
